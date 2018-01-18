<script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
	new TWTR.Widget({
	  version: 2,
	  type: 'list',
	  rpp: 30,
	  interval: 30000,
	  title: 'Latest updates from',
	  subject: 'CCL Clubs',
	  width: 180,
	  height: 300,
	  theme: {
	    shell: {
	      background: '#9E3228',
	      color: '#ffffff'
	    },
	    tweets: {
	      background: '#ffffff',
	      color: '#444444',
	      links: '#444444'
	    }
	  },
	  features: {
	    scrollbar: true,
	    loop: false,
	    live: true,
	    behavior: 'all'
	  }
	}).render().setList('CoCricketLeague', 'Clubs').start();
</script>



