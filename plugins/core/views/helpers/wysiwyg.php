<?php
    class WysiwygHelper extends AppHelper
    {
        var $helpers = array(
            'Form'
        );

        function fck( $id = null, $toolbar = 'Basic' )
        {
            return $this->input( $id ).$this->fckStarter( $id, $toolbar );
        }

        function fckStarter( $id = null, $toolbar = 'Basic' )
        {
            if ( !$id )
            {
                $this->errors[] = 'No id given for the text area';
                return false;
            }

            $did = '';
            foreach ( explode( '.', $id ) as $v )
            {
                $did .= ucfirst( $v );
            }

            return <<<FCK_CODE
<script type="text/javascript">
fckLoader_$did = function () {
    var bFCKeditor_$did = new FCKeditor('$did');
    bFCKeditor_$did.BasePath = '/js/';
    bFCKeditor_$did.ToolbarSet = '$toolbar';
    bFCKeditor_$did.ReplaceTextarea();
}
fckLoader_$did();
</script>
FCK_CODE;
        }

        function text( $id = null )
        {
            return $this->input( $id );
        }

        function input( $id )
        {
            return $this->Form->input( $id, array( 'style' => 'width:100%; height:500px;' ) );
        }
    }
?>