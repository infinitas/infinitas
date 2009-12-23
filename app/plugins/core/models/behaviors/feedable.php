<?php
    /**
     * Model behavior to support generation of feeds from different models.
     *
     * @package app
     * @subpackage app.models.behaviors
     */
    class FeedableBehavior extends ModelBehavior
    {

        function setup(&$Model, $settings = array())
        {
            if (!isset($this->settings[$Model->alias]))
            {
                $this->settings[$Model->alias] = array();
            }

            if ( !is_array( $settings ) )
            {
                $settings = array();
            }
            $this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], $settings);
        }
    }
?>