<?php

use Fuel\Core\Session;

class Model_Permission extends Model_Overwrite
{
    /**
     * RIGHT_WATCH_DISABLED
     * RIGHT_DOWNLOAD_DISABLED
     * RIGHT_MAX_DOWNLOAD
     * RIGHT_MAX_WATCH
     * RIGHT_MAX_QUALITY
     * RIGHT_MAX_CONCURRENT_STREAM
     */

    protected static $_table_name = 'permission';
    protected static $_primary_key = 'id';
    protected static $_properties = array(
        'id',
        'name',
        'disable'
    );

    /**
     * @param string $permission
     * @param Model_Movie $movie
     * @return bool
     */
    public static function isGranted($permission, Model_Library $library = null, $data = null       )
    {
        $user = Session::get('user');

        if ($library === null)
            return false;

        $permission = Model_Permission::find_one_by('name', $permission);

        if ($permission === null)
            return false;

        $library_permission = Model_Library_Permission::find_one_by([
            ['library_id', '=', $library->id],
            ['permission_id', '=', $permission->id],
            ['disable', '=', 0]
        ]);

        if ($permission->name === 'RIGHT_WATCH_DISABLED') {
            if($library_permission !== null && $library_permission->value === 1)
                return false;
            else if($library_permission !== null && $library_permission->value === 0)
                return true;
            else if($library_permission === null)
                return true;
            else
                return false;
        }

        if ($permission->name === 'RIGHT_DOWNLOAD_DISABLED') {
            if($library_permission !== null && $library_permission->value === 1)
                return false;
            else if($library_permission !== null && $library_permission->value === 0)
                return true;
            else if($library_permission === null)
                return true;
            else
                return false;
        }

        if ($permission->name === 'RIGHT_MAX_DOWNLOAD') {
            /** @TODO IF (NUMBER_DOWNLOAD <= MAX_DOWNLOAD)  // in last 24h
             *  RETURN TRUE
             *  ELSE
             *  RETURN FALSE
             */
            return true;
        }

        if ($permission->name === 'RIGHT_MAX_WATCH') {
            /** @TODO IF (NUMBER_WATCH <= MAX_WATCH) // in last 24h
             *  RETURN TRUE
             *  ELSE
             *  RETURN FALSE
             */
            return true;
        }

        if ($permission->name === 'RIGHT_MAX_QUALITY') {
            /** @TODO IF (VIDEO SETTINGS QUALITY <= MAX_QUALITY)
             *  RETURN TRUE
             *  ELSE
             *  RETURN FALSE
             */
            return true;
        }

        if ($permission->name === 'RIGHT_MAX_CONCURRENT_STREAM') {
            /** @TODO IF (CURRENT STREAMING <= MAX_CONCURRENT_STREAM)
             *  RETURN TRUE
             *  ELSE
             *  RETURN FALSE
             */
            return true;
        }

        return false;
    }
}