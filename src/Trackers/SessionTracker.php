<?php namespace Arcanedev\LaravelTracker\Trackers;

use Arcanedev\LaravelTracker\Models\Session;
use Arcanedev\LaravelTracker\Support\PhpSession;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Ramsey\Uuid\Uuid;

/**
 * Class     SessionTracker
 *
 * @package  Arcanedev\LaravelTracker\Trackers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SessionTracker
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var PhpSession */
    private $session;

    private $sessionInfo = [];

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    public function __construct()
    {
        $this->session = new PhpSession;
    }

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
        return config('laravel-tracker.session.name', 'session_name_here');
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

        $this->storeSession();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Track the session.
     *
     * @param  array  $data
     * @param  bool   $updateLastActivity
     *
     * @return int
     */
    public function track(array $data, $updateLastActivity)
    {
        $this->setSessionData($data);

        return $this->getSessionId();
    }

    /**
     * Check the session data.
     *
     * @param  array  $newData
     * @param  array  $currentData
     *
     * @return array
     */
    public function checkData(array $newData, array $currentData)
    {
        return ($newData && $currentData && $newData !== $currentData)
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
        /**  @var \Arcanedev\LaravelTracker\Models\Session $session  */
        $session = $this->checkIfUserChanged($data, Session::find($this->getSessionData('id')));

        $session->update(Arr::except($data, ['id', 'uuid']));

        return $data;
    }

    private function getSessionData($column = null)
    {
        $data = $this->session->get($this->getSessionKey());

        return $column ? Arr::get($data, $column, null) : $data;
    }

    /**
     * Check if user changed.
     *
     * @param  array                                     $data
     * @param  \Arcanedev\LaravelTracker\Models\Session  $model
     *
     * @return \Arcanedev\LaravelTracker\Models\Session  Session
     */
    private function checkIfUserChanged(array $data, $model)
    {
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
     * @param  mixed  $data
     */
    private function putSessionData($data)
    {
        $this->session->put($this->getSessionKey(), $data);
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
        $sessionData = $this->getSessionData();

        return isset($sessionData['uuid']) ? $sessionData['uuid'] : (string) UUID::uuid4();
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

            $this->sessionInfo['id'] = $id;
        }
        else {
            $session = Session::firstOrCreate(Arr::only($this->sessionInfo, ['uuid']), $this->sessionInfo);
            $this->setSessionId($session->id);
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
        if ( ! $this->session->has($this->getSessionKey()))
            return false;

        if ( ! $this->getSessionData('uuid') == $this->getSystemSessionId())
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
     * Store the session information.
     */
    private function storeSession()
    {
        $this->putSessionData($this->sessionInfo);
    }

    /**
     * Generate session data.
     *
     * @param  array  $sessionInfo
     */
    private function generateSession($sessionInfo)
    {
        $this->sessionInfo = $sessionInfo;

        if ( ! $this->checkSessionDataIsReliable()) {
            $this->regenerateSystemSession();
        }

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

        if ( ! $completed) $this->storeSession();
    }
}
