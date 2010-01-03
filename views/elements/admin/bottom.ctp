<div id="bottom-credits">
    <?php
        echo $this->Html->link(
            $this->Html->image(
                'http://www.w3.org/Icons/valid-xhtml10',
                array(
                    'alt' => 'Valid XHTML 1.0 Transitional',
                    'height' => 31,
                    'width' => 88
                )
            ),
            'http://validator.w3.org/check?uri=referer',
            array(
                'escape' => false
            )
        );
    ?>
</div>