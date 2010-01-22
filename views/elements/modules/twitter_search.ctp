<?php
	/**
	* example of basics.
	*
	* values that are not set in config will use defaults
	* {
	*     "title":"Whats the latest buzz...",
	*     "subject":"Infinitas Cms",
	*     "width":"auto",
	*     "height":200,
	*     "body":{"background":"#3086b7","text":"#78b933"},
	*     "tweets":{"background":"#f7f7f7","text":"#444444","links":"#4a36be"}
	* }
	*/
?>
<script src="http://widgets.twimg.com/j/2/widget.js"></script>
<script type="text/javascript">
//<!--
new TWTR.Widget({
  version: <?php echo ( isset($config['version']) ? (int)$config['version'] : 2 ); ?>,
  type: '<?php echo ( isset($config['type']) ? $config['type'] : 'search' ); ?>',
  search: '<?php echo ( isset($config['search']) ? $config['search'] : '#infinitas' ); ?>',
  interval: <?php echo ( isset($config['interval']) ? (int)$config['interval'] : 1000 ); ?>,
  title: '<?php echo ( isset($config['title']) ? $config['title'] : 'Infinitas News' ); ?>',
  subject: '<?php echo ( isset($config['subject']) ? $config['subject'] : 'Infinitas Cms' ); ?>',
  width: '<?php echo ( isset($config['width']) ? $config['width'] : 'auto' ); ?>',
  height: <?php echo ( isset($config['height']) ? (int)$config['height'] : 200 ); ?>,
  theme: {
    shell: {
      background: '<?php echo ( isset($config['body']['background']) ? $config['body']['background'] : '#3086b7' ); ?>',
      color: '<?php echo ( isset($config['body']['text']) ? $config['body']['text'] : '#78b933' ); ?>'
    },
    tweets: {
      background: '<?php echo ( isset($config['tweets']['background']) ? $config['tweets']['background'] : '#f7f7f7' ); ?>',
      color: '<?php echo ( isset($config['tweets']['text']) ? $config['tweets']['text'] : '#444444' ); ?>',
      links: '<?php echo ( isset($config['tweets']['links']) ? $config['tweets']['links'] : '#4a36be' ); ?>'
    }
  },
  features: {
    scrollbar: <?php echo ( isset($config['scrollbar']) ? ( ($config['scrollbar']) ? 'true' : 'false' ) : 'true' ); ?>,
    loop: <?php echo ( isset($config['loop']) ? ( ($config['loop']) ? 'true' : 'false' ) : 'true' ); ?>,
    live: <?php echo ( isset($config['live']) ? ( ($config['live']) ? 'true' : 'false' ) : 'true' ); ?>,
    hashtags: <?php echo ( isset($config['hashtags']) ? ( ($config['hashtags']) ? 'true' : 'false' ) : 'true' ); ?>,
    timestamp: <?php echo ( isset($config['timestamp']) ? ( ($config['timestamp']) ? 'true' : 'false' ) : 'true' ); ?>,
    avatars: <?php echo ( isset($config['avatars']) ? ( ($config['avatars']) ? 'true' : 'false' ) : 'true' ); ?>,
    behavior: '<?php echo ( isset($config['behavior']) ? $config['behavior'] : 'default' ); ?>'
  }
}).render().start();
//-->
</script>