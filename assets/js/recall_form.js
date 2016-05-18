(function() {
	tinymce.create('tinymce.plugins.recall.masters.form', {
		init : function(ed, url) {
			ed.addButton('recall_form', {
				title : 'Recall Form',
				image : url + '/form_button.png',
				onclick: function() {
					ed.execCommand("mceInsertContent", 0, '[recall_form]');
				}
			});
		},
	});
	tinymce.PluginManager.add( 'recall_masters_form', tinymce.plugins.recall.masters.form );
})();
