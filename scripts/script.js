$(document).ready(function() {

    $('#calendar').fullCalendar({
        header: {
			left:   'agendaWeek,month',
	   		center: 'title',
	   		right:  'today prev,next'
        },

        eventLimit: {
        	'default': true
        },


		eventClick: function(data, event, view) {
			var date = new Date(data.start);
			var title = '<b>'+data.title+'</b>';
			var content = '<p><b>Date:</b> '+ date.toLocaleString()+'<br />' + 
				          '<p><a href="' + data.link + '">Github Commit Page</a></p>';

			tooltip.set({
				'content.title': title,
				'content.text': content
			})
			.reposition(event).show(event);
		},

	});

    $('#repo-srch-btn').click(function() {
    	$('#dropdownMenu1').html('Repositories <span class="caret"></span>');
    	var owner = $('#repo-srch-owner').val();
    	var $repoList = $('#dd-repo');
    	$repoList.empty();
    	$.getJSON('https://api.github.com/search/repositories?q=user:' + owner, function(data) {
    		$.each(data.items, function(key, value) {
    			$repoList.append(
    				$('<li>').attr('role','presentation').append(
    					$('<a>')
    						.attr({
    							role: 'menuItem',
    							tabIndex: '-1'    						
    						})
    					  	.html(value.name)
    					  	.click('click', function() {
    					  		$('#calendar').fullCalendar('removeEvents');
    					  		getCommits(owner, value.name);
    					  		setDropDown(value.name);
    					  	})
    				)
    			)
    		});
    	});
    	
    });

    $('#repo-srch-owner').on('keyup', function(e) {
    	if(e.keyCode === 13) {
    		$('#repo-srch-btn').click();
    	}
    });


    function getCommits(owner, repoName) {
    	var events = [];
    	$.getJSON('https://api.github.com/repos/' + owner + '/' + repoName + '/commits', function(data) {
    		$.each(data, function(key, value) {
    			var date = new Date(value.commit.committer.date);
			 	events.push(
			 		{
			 			title: value.commit.message + " -" + value.commit.committer.name,
			 			start: moment(date).format(),
			 			link: value.html_url
			 		}
			 	)
			}); 
			$('#calendar').fullCalendar( 'addEventSource', events );
		})
	}

	function setDropDown(repoName) {
		$('#dropdownMenu1').html(repoName + ' <span class="caret"></span>');
	}

	var tooltip = $('<div/>').qtip({
		id: 'fullcalendar',
		prerender: true,
		content: {
			text: ' ',
			title: {
			}
		},
		position: {
			my: 'center left',
			at: 'center right',
			target: 'event',
			viewport: $('#fullcalendar'),
			adjust: {
				mouse: false,
				scroll: false
			}
		},
		show: false,
		hide: { event: 'unfocus' },
		style: 'qtip-bootstrap'
		}).qtip('api');
    	

});