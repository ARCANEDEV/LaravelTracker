<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Contracts\Trackers\SessionTracker as SessionTrackerContract;
use Arcanedev\LaravelTracker\Models\Session;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Ramsey\Uuid\Uuid;

/**
 * Class     SessionTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SessionTracker extends AbstractTracker implements SessionTrackerContract
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var array */
    private $sessionInfo = [];

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
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
     * Set the session data.
     *
     * @param  array  $data
     */
    private function setSessionData(array $data)
    {
        $this->generateSession($data);

        if ($this->createSessionIfIsUnknown()) {
            $this->ensureSessionDataIsComplete();
        }
    }

    /**
     * Get the session id.
     *
     * @return int
     */
    private function getSessionId()
    {
        return $this->sessionInfo['id'];
    }

    /**
     * Set the session id.
     *
     * @param  mixed  $id
     */
    private function setSessionId($id)
    {
        $this->sessionInfo['id'] = $id;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Track the session.
     *
     * @param  array  $data
     *
     * @return int
     */
    public function track(array $data)
    {
        $this->setSessionData($data);

        return $this->getSessionId();
    }

    /**
     * Check the session data.
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

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Update the session data.
     *
     * @param  array  $data
     *
     * @return array
     */
    private function updateData(array $data)
    {
        $this->checkIfUserChanged($data)
             ->update(Arr::except($data, ['id', 'uuid']));

        return $data;
    }

    /**
     * Get the session data.
     *
     * @param  string|null  $column
     *
     * @return mixed
     */
    private function getSessionData($column = null)
    {
        $data = $this->session()->get($this->getSessionKey());

        return is_null($column) ? $data : Arr::get($data, $column, null);
    }

    /**
     * Check if user changed.
     *
     * @param  array  $data
     *
     * @return \Arcanedev\LaravelTracker\Models\Session
     */
    private function checkIfUserChanged(array $data)
    {
        $model = Session::find($this->getSessionData('id'));

        if (is_null($model) && ! $this->sessionIsKnown())
            return $this->createSessionForGuest($data);

        if (
            ! is_null($model->user_id) &&
            ! is_null($data['user_id']) &&
            $data['user_id'] !== $model->user_id
        ) {
            $newSession = $this->regenerateSystemSession($data);
            $model      = $this->findByUuid($newSession['uuid']);
        }

        return $model;
    }

    /**
     * Create session for guest.
     *
     * @param  array  $data
     *
     * @return \Arcanedev\LaravelTracker\Models\Session
     */
    private function createSessionForGuest(array $data)
    {
        $this->generateSession($data);

        $session = Session::create($this->sessionInfo);

        $this->putSessionData($data);
        $this->setSessionId($session->id);

        return $session;
    }

    /**
     * Regenerate system session.
     *
     * @param  array|null  $data
     *
     * @return array
     */
    private function regenerateSystemSession($data = null)
    {
        $data = $data ?: $this->getSessionData();

        if ($data) {
            $this->resetSessionUuid($data);
            $this->createSessionIfIsUnknown();
        }

        return $this->sessionInfo;
    }

    /**
     * Reset the session uuid.
     *
     * @param  array|null  $data
     *
     * @return array|null
     */
    private function resetSessionUuid($data = null)
    {
        $this->sessionInfo['uuid'] = null;

        $data = $data ?: $this->sessionInfo;

        unset($data['uuid']);

        $this->putSessionData($data);
        $this->checkSessionUuid();

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
     * Check the session uuid.
     */
    private function checkSessionUuid()
    {
        if ( ! isset($this->sessionInfo['uuid']) || ! $this->sessionInfo['uuid'])
            $this->sessionInfo['uuid'] = $this->getSystemSessionId();
    }

    /**
     * Get the system session id.
     *
     * @return string
     */
    private function getSystemSessionId()
    {
        return Arr::get($this->getSessionData(), 'uuid', (string) Uuid::uuid4());
    }

    /**
     * @return bool
     */
    private function createSessionIfIsUnknown()
    {
        /** @var \Arcanedev\LaravelTracker\Models\Session $session */
        if ($known = $this->sessionIsKnown()) {
            $session = Session::find($id = $this->getSessionData('id'));
            $session->updated_at = Carbon::now();
            $session->save();

            $this->setSessionId($id);
        }
        else {
            $session = Session::firstOrCreate(Arr::only($this->sessionInfo, ['uuid']), $this->sessionInfo);
            $this->setSessionId($session->id);
            $this->putSessionData($this->sessionInfo);
        }

        return $known;
    }

    /**
     * Check if the session is known.
     *
     * @return bool
     */
    private function sessionIsKnown()
    {
        if ( ! $this->session()->has($this->getSessionKey()))
            return false;

        if ($this->getSessionData('uuid') != $this->getSystemSessionId())
            return false;

        if ( ! $this->findByUuid($this->getSessionData('uuid')))
            return false;

        return true;
    }

    /**
     * Find a session by its uuid.
     *
     * @param  string  $uuid
     *
     * @return \Arcanedev\LaravelTracker\Models\Session
     */
    private function findByUuid($uuid)
    {
        return Session::where('uuid', $uuid)->first();
    }

    /**
     * Generate session data.
     *
     * @param  array  $sessionInfo
     */
    private function generateSession(array $sessionInfo)
    {
        $this->sessionInfo = $sessionInfo;

        if ( ! $this->checkSessionDataIsReliable())
            $this->regenerateSystemSession();

        $this->checkSessionUuid();
    }

    /**
     * Check if the session data is reliable.
     *
     * @return bool
     */
    private function checkSessionDataIsReliable()
    {
        $data = $this->getSessionData();

        if (isset($data['user_id']) && ($data['user_id'] !== $this->sessionInfo['user_id']))
            return false;

        if (isset($data['client_ip']) && ($data['client_ip'] !== $this->sessionInfo['client_ip']))
            return false;

        if (isset($data['user_agent']) && ($data['user_agent'] !== $this->sessionInfo['user_agent']))
            return false;

        return true;
    }

    /**
     * Ensure that the session data is complete.
     */
    private function ensureSessionDataIsComplete()
    {
        $sessionData = $this->getSessionData();
        $completed   = true;

        foreach ($this->sessionInfo as $key => $value) {
            if ($sessionData[$key] !== $value) {
                /** @var \Arcanedev\LaravelTracker\Models\Session $model */
                if ( ! isset($model))
                    $model = Session::find($this->getSessionId());

                $model->setAttribute($key, $value);
                $model->save();

                $completed = false;
            }
        }

        if ( ! $completed)
            $this->putSessionData($this->sessionInfo);
    }
}
