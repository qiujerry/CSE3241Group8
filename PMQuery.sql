use ParkingMaster;

#user prompted for event, date
 
#CUSTOMER PARKING OPTION QUERY
#PHP user input variables = event name, date (where event.name = "Concert" in t1, where event.eDate = "..." in t2)
select Name as "Garage Name", fee as "Fee ($)", distance as "Distance (mi)" from 
	(select garage.garage_ID, garage.Name, garage.numSpaces, fee.fee, distance.distance
		from venue, event, garage, fee, distance
		where event.name="Concert" and
		venue.venue_ID = event.venue_ID and
		event.event_ID = fee.event_ID and garage.garage_ID = fee.garage_ID
		and venue.venue_ID = distance.venue_ID and garage.garage_ID = distance.garage_ID
	) as t1, 
    (select garage.garage_ID, ifnull(bookCount,0) as bookCount from 
		(select garage_ID, count(concat(reservation.user_ID, reservation.event_ID, reservation.reservation_Date)) as bookCount from reservation, event
				where event.event_ID = reservation.event_ID
				and reservation.reservation_Date = "2022-04-21"
				group by garage_ID
		) as bookCount
		right join garage
		on  bookCount.garage_ID = garage.garage_ID
	) as t2
    where t1.garage_ID = t2.garage_ID and t1.numSpaces > t2.bookCount
    order by t1.distance
    ;
    #query returned
    
    #user makes chooses garage
    select garage_ID from garage
		where Name = userEnteredName;
    
    Insert into RESERVATION (user_ID, garage_ID, event_ID, reservation_Date)
	values (userEnteredID, chosenGarageID, userEnteredEventID, userEnteredDate)
    
    #DEBUGGING
    #compartmentalized queries (for debugging)
    #t1
    select garage.garage_ID, garage.Name, garage.numSpaces, fee.fee, distance.distance
		from venue, event, garage, fee, distance
		where event.name="Concert" and
		venue.venue_ID = event.venue_ID and
		event.event_ID = fee.event_ID and garage.garage_ID = fee.garage_ID
		and venue.venue_ID = distance.venue_ID and garage.garage_ID = distance.garage_ID;
    
    #t2
    #garage.garage_ID, ifnull(bookCount,0)
    select garage.garage_ID, ifnull(bookCount,0) as bookCount from 
		(select garage_ID, count(concat(reservation.user_ID, reservation.event_ID, reservation.reservation_Date)) as bookCount from reservation, event
				where event.event_ID = reservation.event_ID
				and reservation.reservation_Date = "2022-04-21"
				group by garage_ID
		) as bookCount
		right join garage
		on  bookCount.garage_ID = garage.garage_ID;
        
	select count(concat(reservation.user_ID, reservation.event_ID, reservation.reservation_Date)) from reservation;
