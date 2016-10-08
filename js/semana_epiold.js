/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Date.prototype.getWeek = function() {
var onejan = new Date(this.getFullYear(),0,1);
return Math.ceil((((this - onejan) / 86400000) + onejan.getDay()+1)/7);
} 

function getFirstWeek (year) {
    var days, end, start;
    end = new Date(year, 0, 1);
    end.setHours(0, 0, 0, 0);
    days = 0;
    while (++days < 4 || end.getDay() !== 6) {
      end.setDate(end.getDate() + 1);
    }
    start = new Date(end.getTime());
    start.setDate(start.getDate() - 6);
    return start;
  };

  function calculate(myDate, marker) {
    var date = new Date(myDate); // recibe una fecha en formato yyyy/mm/dd
    var fwky_date = new Date(date.getFullYear(),0,1).getDay(); 
    
//    var week = $.datepicker.iso8601Week(new Date(myDate))
    var weekno = date.getWeek(); 

    if (fwky_date >3) {
        if (weekno == 1)
           return "53###"+(date.getFullYear() - 1);
        else 
            return (weekno - 1)+"###"+(date.getFullYear());
    } else
    {
        return (weekno)+"###"+(date.getFullYear());
    }    

//    var date = new Date(myDate); // recibe una fecha en formato yyyy/mm/dd
//    var day, firstWeekNextYear, firstWeekThisYear, week;
//    
//    
//    
//    if (date == null) {
//      date = new Date();
//    }
//    date.setHours(0, 0, 0, 0);
//    firstWeekThisYear = getFirstWeek(date.getFullYear());
//    firstWeekNextYear = getFirstWeek(date.getFullYear() + 1);
//    if (marker == null) {
//      marker = new Date(date.getTime());
//    }
//    marker.setMonth(0, 1);
//    week = 0;
//    while (marker < date) {
//      day = marker.getDay();
//      if (day === 6) {
//        week++;
//      }
//      marker.setDate(marker.getDate() + 1);
//    }
//    if (week === 0) {
//      if (date >= firstWeekThisYear) {
//        return (1)+"###"+date.getFullYear();
////        {
////          week: 1,
////          year: date.getFullYear()
////        };
//      } else {
//        marker.setFullYear(date.getFullYear() - 1, 0, 1);
//        return calculate(date, marker);
//      }
//    } else {
//      if (week >= 51 && date >= firstWeekNextYear) {
//        return (1)+"###"+(date.getFullYear() + 1);
////        {
////          week: 1,
////          year: date.getFullYear() + 1
////        };
//      } else {
//        return (week + 1)+"###"+(date.getFullYear() - (date < firstWeekThisYear ? 1 : 0));
//        
////              {
////          week: week + 1,
////          year: date.getFullYear() - (date < firstWeekThisYear ? 1 : 0)
////        };
//      }
//    }
  };
  
  // recibe la fecha en el formato dd/mm/yyyy y la regresa en yyyy/mm/dd
  function formatDate (myDate){
      return myDate[2]+"/"+myDate[1]+"/"+myDate[0];
  }

