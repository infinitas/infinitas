<?php
    /**
     * AppController
     *
     * @package
     * @author dogmatic
     * @copyright Copyright (c) 2009
     * @version $Id$
     * @access public
     */
    class AppController extends Controller
    {
        var $helpers = array(
            'Html', 'Form', 'Javascript',

            'Core.Status'
        );

        var $components = array(
            // cake components
            'Session',

            //core components
            'DebugKit.Toolbar', 'Core.Cron', 'Core.Config'
        );

        function beforeFilter()
        {
            parent::beforeFilter();

            //$this->Session->write( 'Auth', ClassRegistry::init( 'Core.User' )->find( 'first', array( 'conditions' => array( 'User.id' => 1 ) ) ) );

            $this->__checkUrl();
            $this->__setupLayout();

            $this->__setupCache();

            $this->set( 'commentModel', 'Comment' );
        }

        /**
         * Check the url is www.
         *
         * will redirect to www. if it is not set.
         *
         * @return true;
         */
        private function __checkUrl()
        {

        }

        /**
         * Setup layout based on the prefix.
         *
         * Sets the layout to the corect var based on what path the user visits.
         *
         * @return bool true
         */
        private function __setupLayout()
        {
            $prefix = '';
            if ( isset( $this->params['prefix'] ) )
            {
                $prefix = $this->params['prefix'];
            }
            switch( $prefix )
            {
                case 'admin':
                    $this->layout = 'admin';
                    break;

                case 'client':
                    $this->layout = 'client';
                    break;

                default:
                    $this->layout = 'default';
            } // switch

            return true;
        }

        private function __setupCache()
        {
            Cache::config(
                'cms',
                array(
             		'engine' => 'File',
             		'duration'=> 3600,
             		'probability'=> 100,
              		'prefix' => '',
              		'lock' => false,
              		'serialize' => true,
              		'path' => CACHE.'cms'
                )
            );

            Cache::config(
                'core',
                array(
             		'engine' => 'File',
             		'duration'=> 3600,
             		'probability'=> 100,
              		'prefix' => '',
              		'lock' => false,
              		'serialize' => true,
              		'path' => CACHE.'core'
                )
            );

            Cache::config(
                'blog',
                array(
             		'engine' => 'File',
             		'duration'=> 3600,
             		'probability'=> 100,
              		'prefix' => '',
              		'lock' => false,
              		'serialize' => true,
              		'path' => CACHE.'blog'
                )
            );
        }



        /**
        * Common methods for the app
        */

        protected function comment()
        {
            if ( !empty( $this->data ) )
            {
                if ( !isset( $this->data[Inflector::classify( $this->name )]['id'] ) )
                {
                    $this->Session->setFlash( __( 'There is a problem with your comment.', true ) );
                    $this->redirect( $this->referer() );
                }

                $this->data['Comment']['foreign_id'] = $this->data[Inflector::classify( $this->name )]['id'];
                $this->data['Comment']['class']      = $this->__getClassName();

                $comment['Comment'] = $this->data['Comment'];

                ClassRegistry::init( 'Core.Comment' )->create();

                if ( ClassRegistry::init( 'Core.Comment' )->save( $comment ) )
                {
                    $this->Session->setFlash( __( 'Your comment has been saved for review.', true ) );
                    $this->redirect( $this->referer() );
                }

                $this->Session->setFlash( __( 'Your comment could not be saved, please try again.', true ) );
                $this->redirect( $this->referer() );
            }
        }

        function __getClassName()
        {
            if ( isset( $this->params['plugin'] ) )
            {
                return Inflector::classify( $this->params['plugin'] ).'.'.Inflector::classify( $this->name );
            }
            else
            {
                return Inflector::classify( $this->name );
            }
        }

        /**
         * reorder records.
         *
         * uses named paramiters can use the following:
         * - up:       moves the record up.
         * - down:     moves the record down.
         * - position: sets the position for the record.
         *
         * @param int $id the id of the record to move.
         *
         * @return does a redirect to the referer.
         */
        protected function admin_reorder( $id = null )
        {
            $model = $this->modelNames[0];

            if ( !$id )
            {
                $this->Session->setFlash( 'That '.$model.' could not be found', true );
                $this->redirect( $this->referer() );
            }

            $this->$model->id = $id;

            if ( !isset( $this->params['named']['direction'] ) )
            {
                $this->Session->setFlash( __( 'Please select the direction you would like to move the record.', true ) );
                $this->redirect( $this->referer() );
            }

            $amount = ( isset( $this->params['named']['amount'] ) ) ? $this->params['named']['amount'] : 1;

            switch( $this->params['named']['direction'] )
            {
                case 'position':
                    /**
                    * @todo set the position of the record after add
                    */
                    break;

                case 'up':
                    $this->$model->moveup( $id, $amount );
                    break;

                case 'down':
                    $this->$model->movedown( $id, $amount );
                    break;
            } // switch

            $this->redirect( $this->referer() );
        }
        /**
         * toggle records with an active table that is tinyint(1).
         *
         * @todo -cAppController Implement AppController.
         * - check the table has "active" field
         * - check its tinyint(1)
         * - make better with saveField and not reading the whole record.
         *
         * @param mixed $id the id of the record
         * @return n/a, redirects with different messages in {@see Session::setFlash}
         */
        protected function admin_toggle( $id = null )
        {
            $model = $this->modelNames[0];

            if ( !$id )
            {
                $this->Session->setFlash( 'That '.$model.' could not be found', true );
                $this->redirect( $this->referer() );
            }

            $this->$model->id = $id;
            $this->$model->recursive = -1;
            $__data = $this->$model->read();
            $__data[$model]['active'] = ( $__data[$model]['active'] ) ? 0 : 1;

            if ( $this->$model->save( $__data, array( 'validate' => false ) ) )
            {
                $this->Session->setFlash( sprintf( __( 'The '.$model.' is now %s', true ), ( ( $__data[$model]['active'] ) ? __( 'active', true ) : __( 'disabled', true ) ) ) );
                $this->redirect( $this->referer() );
            }

            $this->Session->setFlash( 'That '.$model.' could not be toggled', true );
            $this->redirect( $this->referer() );
        }

        /**
         * delete records.
         *
         * delete records throughout the app.
         *
         * @todo -cAppController Implement AppController.
         * - make a confirm if the js box does not happen. eg open delete in new
         *   window there is no confirm, just delete.
         * - undo thing... maybe save the whole record in the session and if click
         *   undo just save it back, or use soft delete and purge
         *
         * @param mixed $id the id of the record.
         * @return n/a just redirects with different messages in {@see Session::setFlash}
         */
        protected function admin_delete( $id = null )
        {
            $model = $this->modelNames[0];

            if ( !$id )
            {
                $this->Session->setFlash( 'That '.$model.' could not be found', true );
                $this->redirect( $this->referer() );
            }

            if ( $this->$model->delete( $id ) )
            {
                $this->Session->setFlash( __( 'The '.$model.' has been deleted', true ) );
                $this->redirect( array( 'action' => 'index' ) );
            }
        }
    }
?>