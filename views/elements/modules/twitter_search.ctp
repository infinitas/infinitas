<script src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
new TWTR.Widget({
  version: 2,
  type: 'search',
  search: '#infinitas',
  interval: 5000,
  title: 'Whats the latest buzz...',
  subject: 'Infinitas cms',
  width: 'auto',
  height: 200,
  theme: {
    shell: {
      background: '#3086b7',
      color: '#78b933'
    },
    tweets: {
      background: '#f7f7f7',
      color: '#444444',
      links: '#4a36be'
    }
  },
  features: {
    scrollbar: false,
    loop: true,
    live: true,
    hashtags: true,
    timestamp: true,
    avatars: true,
    behavior: 'default'
  }
}).render().start();
</script>