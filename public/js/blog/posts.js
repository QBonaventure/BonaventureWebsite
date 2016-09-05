$(document).ready(function() {
	textareaResize()
	setPublicateSwitches()
	setTableFiltersUpdate('posts-filters', 'postsTable', true)
	textareaResize()
});


function setPublicateSwitches() {
	jQuery('.switcher').each(function(index, button) {
		setSwitch(button)
	})
}


function setSwitch(button) { 
	$(button).dblclick(function() {
		$(button).off('dblclick')
		var action	= button.alt
		var post_id	= $(button).parents(".row")[0].id.replace('post-', '')
		var params	= {action: action,
						post_id: post_id};
		jQuery.ajax({url: window.location.href,
					 method: 'post',
					 data: params})
			.done(function(data) {
				if(action == 'publish') {
					$(button).replaceWith('<img alt="unpublish" class="switcher" src="/css/buttons/down.png">')
					setSwitch(jQuery('#post-'+post_id+' img[alt="unpublish"]')[0])
				}
				else if (action == 'unpublish') {
					$(button).replaceWith('<img alt="publish" class="switcher" src="/css/buttons/up.png">')
					setSwitch(jQuery('#post-'+post_id+' img[alt="publish"]')[0])
				}
				else if (action == 'delete') {
					jQuery('#post-'+post_id)[0].remove()
				}
		})
			.fail(function(data) {
				setSwitch(button)
			})
//			.always(function(data) {
//				alert('lkjlkj');
//			})
	})
}

function add_keyword() {
    var currentCount = $('form div#keywords-fieldset > fieldset').length;
    var template = $('form div#keywords-fieldset span').data('template');
    template = template.replace(/__index__/g, currentCount);

    $('form div#keywords-fieldset > fieldset').append(template);

    return false;
}


function adjustHeight(textareaElement, minHeight) {
    // compute the height difference which is caused by border and outline
    var outerHeight = parseInt(window.getComputedStyle(textareaElement).height, 10)
    var diff = outerHeight - textareaElement.clientHeight
    // set the height to 0 in case of it has to be shrinked
    textareaElement.style.height = 0
    // set the correct height
    // el.scrollHeight is the full height of the content, not just the visible part
    textareaElement.style.height = Math.max(minHeight, textareaElement.scrollHeight + diff) + 'px'
}

function textareaResize() {
    var textAreas = $('textarea')
    textAreas.each(function(index, textareaElement) {
//        textareaElement.style.boxSizing = textareaElement.style.mozBoxSizing = 'border-box'

        // the minimum height initiated through the "rows" attribute
        var minHeight = textareaElement.scrollHeight
        textareaElement.addEventListener('input', function() {
            adjustHeight(textareaElement, minHeight)
        })
        
        // we have to readjust when window size changes (e.g. orientation change)
        window.addEventListener('resize', function() {
            adjustHeight(textareaElement, minHeight)
        })
        
        // we adjust height to the initial content
        adjustHeight(textareaElement, minHeight)
    })
}




function preview_post() {
    var form	= $('form')
    var yut = form.find('input[name="post[lead]"]').val()
    var params	= { action: 'preview',
        			title: form.find('input[name="post[title]"]').val(),
   	 			lead: form.find('textarea[name="post[lead]"]').val(),
   	 			body: form.find('textarea[name="post[body]"]').val(),
   	           }
	jQuery.ajax({url: window.location.href+'/preview',
		 method: 'post',
		 data: params})
	.done(function(data) {
		$('body').append(data.preview)
		$('#post-preview').click(function() {
			$('#post-preview').remove()
		})
	})
	.always(function(data) {
		$('body').append(data)
		$('#post-preview').click(function() {
			$('#post-preview').remove()
		})
	})
}