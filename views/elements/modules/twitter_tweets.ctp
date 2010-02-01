<script src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
//<!--
new TWTR.Widget({
  version: <?php echo ( isset($config['version']) ? (int)$config['version'] : 2 ); ?>,
  type: '<?php echo ( isset($config['type']) ? $config['type'] : 'profile' ); ?>',
  rpp: 10,
  interval: <?php echo ( isset($config['interval']) ? (int)$config['interval'] : 5000 ); ?>,
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
    scrollbar: <?php echo ( isset($config['scrollbar']) ? ( ($config['scrollbar']) ? 'true' : 'false' ) : 'false' ); ?>,
    loop: <?php echo ( isset($config['loop']) ? ( ($config['loop']) ? 'true' : 'false' ) : 'true' ); ?>,
    live: <?php echo ( isset($config['live']) ? ( ($config['live']) ? 'true' : 'false' ) : 'true' ); ?>,
    hashtags: <?php echo ( isset($config['hashtags']) ? ( ($config['hashtags']) ? 'true' : 'false' ) : 'true' ); ?>,
    timestamp: <?php echo ( isset($config['timestamp']) ? ( ($config['timestamp']) ? 'true' : 'false' ) : 'true' ); ?>,
    avatars: <?php echo ( isset($config['avatars']) ? ( ($config['avatars']) ? 'true' : 'false' ) : 'true' ); ?>,
    behavior: '<?php echo ( isset($config['behavior']) ? $config['behavior'] : 'default' ); ?>'
  }
}).render().setUser('infinit8s').start();
-->
</script>