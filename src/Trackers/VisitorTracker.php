<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Trackers\VisitorTracker as VisitorTrackerContract;
use Arcanedev\LaravelTracker\Support\BindingManager;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Ramsey\Uuid\Uuid;

/**
 * Class     VisitorTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class VisitorTracker extends AbstractTracker implements VisitorTrackerContract
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  array */
    private $visitorInfo = [];

    /* -----------------------------------------------------------------
     |  Getters and Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the model.
     *
     * @return \Arcanedev\LaravelTracker\Models\Visitor
     */
    protected function getModel()
    {
        return $this->makeModel(BindingManager::MODEL_VISITOR);
    }

    /**
     * Get the session key.
     *
     * @return string
     */
    private function getSessionKey()
    {
        return $this->getConfig('session.name', 'tracker_session');
    }

    /**
     * Set the visitor data.
     *
     * @param  array  $data
     */
    private function setVisitorData(array $data)
    {
        $this->generateVisitor($data);

        if ($this->createVisitorIfIsUnknown()) {
            $this->ensureVisitorDataIsComplete();
        }
    }

    /**
     * Get the visitor id.
     *
     * @return int
     */
    private function getVisitorId()
    {
        return $this->visitorInfo['id'];
    }

    /**
     * Set the visitor id.
     *
     * @param  mixed  $id
     */
    private function setVisitorId($id)
    {
        $this->visitorInfo['id'] = $id;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Track the visitor.
     *
     * @param  array  $data
     *
     * @return int
     */
    public function track(array $data)
    {
        $this->setVisitorData($data);

        return $this->getVisitorId();
    }

    /**
     * Check the visitor data.
     *
     * @param  array  $currentData
     * @param  array  $newData
     *
     * @return array
     */
    public function checkData(array $currentData, array $newData)
    {
        return ($currentData && $newData && $currentData !== $newData)
            ? $this->updateData($newData)
            : $newData;
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Update the visitor data.
     *
     * @param  array  $data
     *
     * @return array
     */
    private function updateData(array $data)
    {
        $this->checkIfUserChanged($data);

        return $data;
    }

    /**
     * Get the visitor data.
     *
     * @param  string|null  $column
     *
     * @return mixed
     */
    private function getVisitorData($column = null)
    {
        $data = $this->session()->get($this->getSessionKey());

        return is_null($column) ? $data : Arr::get($data, $column, null);
    }

    /**
     * Check if user changed.
     *
     * @param  array  $data
     */
    private function checkIfUserChanged(array $data)
    {
        $model = $this->getModel()->newQuery()->find($this->getVisitorData('id'));

        if (
            ! is_null($model) &&
            ! is_null($model->user_id) &&
            ! is_null($data['user_id']) &&
            $data['user_id'] !== $model->user_id
        ) {
            $newVisitor = $this->regenerateSystemVisitor($data);
            $model      = $this->findByUuid($newVisitor['uuid']);
            $model->update(Arr::except($data, ['id', 'uuid']));
        }
    }

    /**
     * Regenerate visitor data for the system.
     *
     * @param  array|null  $data
     *
     * @return array
     */
    private function regenerateSystemVisitor($data = null)
    {
        $data = $data ?: $this->getVisitorData();

        if ($data) {
            $this->resetVisitorUuid($data);
            $this->createVisitorIfIsUnknown();
        }

        return $this->visitorInfo;
    }

    /**
     * Reset the visitor uuid.
     *
     * @param  array|null  $data
     *
     * @return array|null
     */
    private function resetVisitorUuid($data = null)
    {
        $this->visitorInfo['uuid'] = null;

        $data = $data ?: $this->visitorInfo;

        unset($data['uuid']);

        $this->putSessionData($data);
        $this->checkVisitorUuid();

        return $data;
    }

    /**
     * Put the session data.
     *
     * @param  array  $data
     */
    private function putSessionData(array $data)
    {
        $this->session()->put([
            $this->getSessionKey() => $data
        ]);
    }

    /**
     * Check the visitor uuid.
     */
    private function checkVisitorUuid()
    {
        if ( ! isset($this->visitorInfo['uuid']) || ! $this->visitorInfo['uuid'])
            $this->visitorInfo['uuid'] = $this->getVisitorIdFromSystem();
    }

    /**
     * Get the visitor id from the system.
     *
     * @return string
     */
    private function getVisitorIdFromSystem()
    {
        return Arr::get($this->getVisitorData(), 'uuid', (string) Uuid::uuid4());
    }

    /**
     * Create a new visitor if is unknown.
     *
     * @return bool
     */
    private function createVisitorIfIsUnknown()
    {
        $model = $this->getModel();

        /** @var  \Arcanedev\LaravelTracker\Models\Visitor  $visitor */
        if ($this->isVisitorKnown()) {
            $visitor = $model->newQuery()->find($id = $this->getVisitorData($model->getKeyName()));
            $visitor->updated_at = Carbon::now();
            $visitor->save();

            $this->setVisitorId($id);

            return true;
        }

        $visitor = $model->newQuery()
            ->firstOrCreate(Arr::only($this->visitorInfo, ['uuid']), $this->visitorInfo);

        $this->setVisitorId($visitor->getKey());
        $this->putSessionData($this->visitorInfo);

        return false;
    }

    /**
     * Check if the visitor is known.
     *
     * @return bool
     */
    private function isVisitorKnown()
    {
        if ( ! $this->session()->has($this->getSessionKey()))
            return false;

        if ($this->getVisitorData('uuid') != $this->getVisitorIdFromSystem())
            return false;

        if ( ! $this->findByUuid($this->getVisitorData('uuid')))
            return false;

        return true;
    }

    /**
     * Find a visitor by its uuid.
     *
     * @param  string  $uuid
     *
     * @return \Arcanedev\LaravelTracker\Models\Visitor
     */
    private function findByUuid($uuid)
    {
        return $this->getModel()->where('uuid', $uuid)->first();
    }

    /**
     * Generate visitor data.
     *
     * @param  array  $visitorInfo
     */
    private function generateVisitor(array $visitorInfo)
    {
        $this->visitorInfo = $visitorInfo;

        if ( ! $this->checkVisitorDataIsReliable())
            $this->regenerateSystemVisitor();

        $this->checkVisitorUuid();
    }

    /**
     * Check if the visitor data is reliable.
     *
     * @return bool
     */
    private function checkVisitorDataIsReliable()
    {
        $data = $this->getVisitorData();

        foreach (['user_id', 'client_ip', 'user_agent'] as $key) {
            if ($this->checkDataIsUnreliable($data, $key)) return false;
        }

        return true;
    }

    /**
     * Check the data is unreliable.
     *
     * @param  array|null  $data
     * @param  string      $key
     *
     * @return bool
     */
    private function checkDataIsUnreliable($data, $key)
    {
        return isset($data[$key]) && ($data[$key] !== $this->visitorInfo[$key]);
    }

    /**
     * Ensure that the visitor data is complete.
     */
    private function ensureVisitorDataIsComplete()
    {
        $visitorData = $this->getVisitorData();
        $completed   = true;

        foreach ($this->visitorInfo as $key => $value) {
            if ($visitorData[$key] !== $value) {
                /** @var  \Arcanedev\LaravelTracker\Models\Visitor  $model */
                if ( ! isset($model))
                    $model = $this->getModel()->find($this->getVisitorId());

                $model->setAttribute($key, $value);
                $model->save();

                $completed = false;
            }
        }

        if ( ! $completed)
            $this->putSessionData($this->visitorInfo);
    }
}
