function tiny_TT() {
    return "[tiny-TT]";
}
(function() {
	tinymce.create('tinymce.plugins.tinyTT', {
		init : function(ed, url) {
			// Register commands
			ed.addCommand('mceHighlight', function() {
				ed.windowManager.open({
					file : '?TTtinymce=yes',
					width : 300 + parseInt(ed.getLang('highlight.delta_width', 0)),
					height : 100 + parseInt(ed.getLang('highlight.delta_height', 0)),
					inline : 1,
					title : 'Select store'
				}, {
					title : 'Select store',
					plugin_url : url
				});
			});
			// Register buttons
			ed.addButton('tinyTT', {title : 'Insert store', cmd : 'mceHighlight', image: url + '/cart.png' });
			},
		});
	// Register plugin
	tinymce.PluginManager.add('tinyTT', tinymce.plugins.tinyTT);
})();