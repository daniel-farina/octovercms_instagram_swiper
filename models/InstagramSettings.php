<?php
namespace DanielFarina\Instagram\Models;
use Model;


class InstagramSettings extends Model{
    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'danielfarina_instagram';

    public $settingsFields = 'fields.yaml';
}
?>