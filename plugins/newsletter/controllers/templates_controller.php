<?php
    class TemplatesController extends NewsletterAppController
    {
        var $name = 'Templates';

        var $version = '0.5';

        var $helpers = array(
        );

        private $sampleText = '<p>This is some sample text to test your template</p>';

        function beforeFilter()
        {
            parent::beforeFilter();
        }

        function admin_index()
        {
            $this->paginate = array(
                'fields' => array(
                    'Template.id',
                    'Template.name',
                    'Template.locked',
                    'Template.locked_by',
                    'Template.locked_since',
                    'Template.created',
                    'Template.modified',
                )
            );

            $templates = $this->paginate( 'Template' );

            $this->set( compact( 'templates' ) );
        }

        function admin_add()
        {
            if ( !empty( $this->data ) )
            {
                $this->Template->create();
                if ( $this->Template->save( $this->data ) )
                {
                    $this->Session->setFlash( __( 'Your template has been saved.', true ) );
                    $this->redirect( array( 'action' => 'index' ) );
                }
            }
        }

        function admin_edit( $id = null )
        {
            if ( !$id )
            {
                $this->Session->setFlash( __( 'Please select a template', true ) );
                $this->redirect( $this->referer() );
            }

            if ( !empty( $this->data ) )
            {
                $this->Template->create();
                if ( $this->Template->save( $this->data ) )
                {
                    $this->Session->setFlash( __( 'Your template has been saved.', true ) );
                    $this->redirect( array( 'action' => 'index' ) );
                }
            }

            if ( $id && empty( $this->data ) )
            {
                $this->Template->recursive = -1;
                $this->data = $this->Template->lock( null, $id );
                if ( $this->data === false )
                {
                    $this->Session->setFlash( __( 'The template is currently locked', true ) );
                    $this->redirect( $this->referer() );
                }
            }
        }

        function admin_view( $id = null )
        {
            if ( !$id )
            {
                $this->Session->setFlash( __( 'Please select a template', true ) );
                $this->redirect( $this->referer() );
            }

            $this->set( 'template', $this->Template->read( null, $id ) );
        }

        function admin_export( $id = null )
        {
            if ( !$id )
            {
                $this->Session->setFlash( __( 'Please select a template', true ) );
                $this->redirect( $this->referer() );
            }

            $this->Template->recursive = -1;
            $template = $this->Template->read( array( 'name', 'description', 'author', 'header', 'footer' ), $id );

            if ( empty( $template ) )
            {
                $this->Session->setFlash( __( 'The template does not exist.', true ) );
                $this->redirect( $this->referer() );
            }

            $pattern = "/src=[\\\"']?([^\\\"']?.*(png|jpg|gif|jpeg))[\\\"']?/i";
            preg_match_all( $pattern, $template['Template']['header'], $images );


            $path = TMP.'cache'.DS.'newsletter'.DS.'template'.DS.$template['Template']['name'];

            $Folder = new Folder( $path, 0777 );
            $slash = $Folder->correctSlashFor( $path );

            App::import( 'File' );
            App::import( 'Folder' );

            $File = new File( $path.DS.'template.xml', true, 0777 );

            $image_files = array();

            if ( !empty( $images[1] ) )
            {
                foreach( $images[1] as $img )
                {
                    $img = str_replace( '/', $slash, $img );
                    $img = str_replace( '\\', $slash.$slash, $img );

                    $image_files[] = $img;

                    if ( is_file( APP.'webroot'.$img ) )
                    {
                        $Folder->create( dirname( $path.$img ), 0777 );
                        $File->path = APP.'webroot'.$img;
                        $File->copy( dirname( $path.$img ).DS.basename( $img ) );
                    }
                }
            }

            $xml['template']['name']        = 'Infinitas Newsletter Template';
            $xml['template']['generator']   = 'Infinitas Template Generator';
            $xml['template']['version']     = $this->version;
            $xml['template']['template']    = $template['Template']['name'];
            $xml['template']['description'] = $template['Template']['description'];
            $xml['template']['author']      = $template['Template']['author'];
            $xml['data']['header']          = $template['Template']['header'];
            $xml['data']['footer']          = $template['Template']['footer'];
            $xml['files']['images']         = $image_files;

            App::Import( 'Helper', 'Xml' );
            $Xml = new XmlHelper();

            $xml_string = $Xml->serialize( $xml );

            $File->path = $path.DS.'template.xml';
            $File->write( $xml_string );

            App::import( 'Vendor', 'Zip', array( 'file' => 'zip.php' ) );

            $Zip = new CreateZipFile();
            $Zip->zipDirectory( $path, null );
            $File = new File( $path.DS.'template.zip', true, 0777 );
            $File->write( $Zip->getZippedfile() );

            $this->view = 'Media';
            $params = array(
                'id' => 'template.zip',
                'name' => $template['Template']['name'],
                'download' => true,
                'extension' => 'zip',
                'path' => $path.DS
            );

            $this->set( $params );
            $Folder = new Folder( $path );
            $Folder->read();
            $Folder->delete( $path );
        }

        function admin_preview( $id = null )
        {
            $this->layout = 'ajax';

            if ( !$id )
            {
                $this->set( 'data', __( 'The template was not found', true ) );
            }
            else
            {
                $template = $this->Template->read( array( 'header', 'footer' ), $id );
                $this->set( 'data', $template['Template']['header'].$this->sampleText.$template['Template']['footer'] );
            }

        }
    }
?>