$(function() {

    // Datepicker
    $(".datepicker").datepicker({
        showOtherMonths: true,
        dateFormat: "d MM, y"
    });


    // Inline datepicker
    $(".datepicker-inline").datepicker({
        showOtherMonths: true,
        defaultDate: '07/26/2015'
    });


    // Switchery toggle
    var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
    elems.forEach(function(html) {
        var switchery = new Switchery(html);
    });

	
	if($("#add-comment").length > 0){
		// CKEditor
		CKEDITOR.replace( 'add-comment', {
			height: '200px',
			removeButtons: 'Subscript,Superscript',
			toolbarGroups: [
				{ name: 'styles' },
				{ name: 'editing',     groups: [ 'find', 'selection' ] },
				{ name: 'forms' },
				{ name: 'basicstyles', groups: [ 'basicstyles' ] },
				{ name: 'paragraph',   groups: [ 'list', 'blocks', 'align' ] },
				{ name: 'links' },
				{ name: 'insert' },
				{ name: 'colors' },
				{ name: 'tools' },
				{ name: 'others' },
				{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] }
			]
		});
	}
	
	

    // Select2 select
    // ------------------------------

    // Basic
    $('.select').select2();


    //
    // Select with icons
    //

    // Initialize
    $(".select-icons").select2({
        formatResult: iconFormat,
        minimumResultsForSearch: "-1",
        width: '100%',
        formatSelection: iconFormat,
        escapeMarkup: function(m) { return m; }
    });

    // Format icons
    function iconFormat(state) {
        var originalOption = state.element;
        return "<i class='icon-" + $(originalOption).data('icon') + "'></i>" + state.text;
    }



    // Styled form components
    // ------------------------------

    // Checkboxes, radios
    $(".styled").uniform({ radioClass: 'choice' });

    // File input
    $(".file-styled").uniform({
        fileButtonHtml: '<i class="icon-googleplus5"></i>',
        wrapperClass: 'bg-warning'
    });

});