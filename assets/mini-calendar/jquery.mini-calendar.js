$(function(){
    
    $.fn.miniCalendar = function(settings) {
        
        // This should all really be within the loop
        var dayData = {},
            dataComplete = $.Deferred(),
            currentDate = { month: new Date().getMonth(), year: new Date().getFullYear() };
            settings = $.extend({
                            url: '',
                            data: {},
                            events: [],
                            dayOnMouseOver: function(evt, data){},
                            dayOnMouseOut: function(evt, data){},
                            dayOnClick: function(evt, data){},
                            error: function(error){}
                        }, settings);
            
        function renderMonth(ele, month, year) {
            
            var months = new Array("January", "February", "March", "April", "May", 
                                   "June", "July", "August", "September", "October", "November", "December");
    
            // Fill in the blanks if needed
            if(typeof month == 'number') currentDate.month = month;
            if(typeof year == 'number') currentDate.year = year;
            
            // Week day map & start of the requested month
            var monthStart = new Date(currentDate.year, currentDate.month, 1);
            
            // Calculate the offset from the start of the week
            var startFrom = new Date(currentDate.year, currentDate.month, (monthStart.getDate()) - monthStart.getDay());
            
            // Calculate how many days we need to count forward & whether we're short on filling a row      
            var daysToProcess = ((new Date(currentDate.year, currentDate.month + 1, 0).getDate()) + monthStart.getDay());      
    
            if(new Date(startFrom.getFullYear(), startFrom.getMonth(), startFrom.getDate() + daysToProcess).getDay() <= 6) {
                daysToProcess += (6 - new Date(startFrom.getFullYear(), startFrom.getMonth(), startFrom.getDate() + daysToProcess).getDay());
            }
            
            // Build our table
            var calendar = $('<table></table>'), tr;
            
            // Add month name
            ele.append($('<div></div>').html(months[currentDate.month]).addClass('month-name'));
            
            // Add a header
            var th = $('<tr></tr>').addClass('header').append($('<td></td>').html('Sun')).append($('<td></td>').html('Mon')).append($('<td></td>').html('Tue')).append($('<td></td>').html('Wed'))
            .append($('<td></td>').html('Thu')).append($('<td></td>').html('Fri')).append($('<td></td>').html('Sat'));
            calendar.append(th);
            
            for(var i=0; i <= daysToProcess; i++) {
                
                // Create a new row for each week
                if(i / 7 == parseInt(i / 7)){             
                    calendar.append(tr);
                    tr = $('<tr></tr>');                
                }
                
                var td = $('<td></td>').html(startFrom.getDate()).attr('data-date', startFrom.getFullYear() + '-' + startFrom.getMonth() + '-' + startFrom.getDate());
                
                // If the day is not part of the requested month mark it
                if(startFrom.getMonth() != currentDate.month) td.addClass('filler-day');
                tr.append(td);   
                
                startFrom = new Date(startFrom.getFullYear(), startFrom.getMonth(), startFrom.getDate() + 1);
            }        
        
           // Add the final row
           calendar.append(tr);
            
           ele.append(calendar).addClass('mini-calendar');
            
        }
        
        function splitDate(str) {
            
            var v = str.split(' '),
                date = v[0].split('-'),
                time = v[1].split(':');
            
            date[1]--;
            
            return date.concat(time);
            
        }
        
        function attachData(ele) {
            
            // !important We should validate the data here
            
            for(var i=0; i < settings.events.length; i++) {
                
                // Calculate it's date range
                var start = new Date(settings.events[i].start),
                    end = new Date(settings.events[i].end),
                    startDay = true;
                
                while(start <= end) {
                 
                    // Mark the date
                    var dateSlug = start.getFullYear() + '-' + start.getMonth() + '-' + start.getDate();
                    ele.find('td[data-date=' + dateSlug + ']').addClass('selected');
                    
                    if(typeof dayData[dateSlug] != 'object') {
                        dayData[dateSlug] = [];
                    }
                    
                    // Add the day data to our object
                    dayData[dateSlug].push($.extend({ startDay: startDay }, settings.events[i]));
                    
                    start = new Date(start.getFullYear(), start.getMonth(), start.getDate() + 1);
                    startDay = false;
                    
                }
            
            }
            
            dataComplete.resolve();
            
        }
        
        function plugEvents(ele) {
            
            ele.find('td').bind('click mouseover mouseout', function(evt){
                
                if(dayData[$(this).attr('data-date')]) {
                    
                    switch(evt.type)
                    {
                        case 'click':
                            settings.dayOnClick(evt, dayData[$(this).attr('data-date')]);
                            break;
                        case 'mouseover':                            
                            settings.dayOnMouseOver(evt, dayData[$(this).attr('data-date')]);
                            break;
                        case 'mouseout':                            
                            settings.dayOnMouseOut(evt, dayData[$(this).attr('data-date')]);
                            break;                            
                    }
                }
            });
            
        }
        
        function getData() {
         
            // Request the data
            $.ajax({
              dataType: "json",
              url: settings.url,
              data: $.extend({ start: (new Date(currentDate.year, currentDate.month, 1).getTime() / 1000), 
                               end: (new Date(currentDate.year, currentDate.month + 1, 0).getTime() / 1000), 
                               _:  new Date(currentDate.year, currentDate.month + 1, 0).getTime() }, 
                    settings.data),
              success: function(data){ settings.events = data; dataComplete.notify(); },
              error: function(data){ dataComplete.reject(data); }
            });
            
        }
        
        // Process each passed element and maintain the chain
        return this.each( function() {     
            
            var self = $(this);
            
            // Hook up the promises for the data
            dataComplete.promise().progress(function(){
                
                // Attach the data to the calendar                
                attachData(self);
                
            }).done(function(){
                
                // Finally plug the events
                plugEvents(self);
                
            }).fail(function(data) {
                
                // Call an error
                settings.error(data);
                
            });       
            
            // Render the calendars view
            renderMonth(self);
            
            // Get the data if needed
            if(settings.url.length > 0) {
                getData();
            } else {
                dataComplete.notify();   
            }
            
        });
        
        
        
    }
    
});