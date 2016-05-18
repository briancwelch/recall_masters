(function() {
	tinymce.create('tinymce.plugins.recall.masters.results', {
		init : function(ed, url) {
			ed.addButton('recall_results', {
				title : 'Recall Results',
				image : url + '/results_button.png',
				onclick: function() {
					ed.execCommand("mceInsertContent", 0, '[recall_results]');
				}
			});
		},
	});
	tinymce.PluginManager.add( 'recall_masters_results', tinymce.plugins.recall.masters.results );
})();
