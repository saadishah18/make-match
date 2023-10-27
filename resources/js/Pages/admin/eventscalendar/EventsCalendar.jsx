import React, {useEffect, useState} from 'react';
import '@fullcalendar/react/dist/vdom';
import FullCalendar, { formatDate } from '@fullcalendar/react'
import dayGridPlugin from '@fullcalendar/daygrid'
import { Calendar } from '@fullcalendar/core';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import { Head, Link } from '@inertiajs/inertia-react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import moment from "moment";
import AssignImamModal from "@/Components/AssignImamModal";
import EventDetail from "@/Components/EventDetail";

export default function EventsCalendar(props) {
    // console.log({props});
    const[weekendsVisible, setWeekendsVisible] = useState(true);
    const[currentEvents, setcurrentEvents] = useState([]);
    const [eventList, setEventList] = useState([]);
    const [nikahDetail, setNikahDetail] = useState([]);
    const [showDetail, setShowDetail] = useState(false);

    let eventGuid = 0
    // let todayStr = new Date().toISOString().replace(/T.*$/, '') // YYYY-MM-DD of today

    function createEventId() {
        return String(eventGuid++)
    }

    function closeModal() {
        setShowDetail(false)
    }

    useEffect(() => {
        // setLoader(true)
        let events = [];

        for(event of props?.nikahs?.data){
            let dateObj = moment(event.nikah_date).format('YYYY-MM-DD');
            let starttimeObject = moment(event.start_time_simple).format('HH:mm:ss');
            let endTime = moment(event.end_time_simple).format('HH:mm:ss');
            events.push(
                {
                    id: event.nikah_id,
                    title: event.nikah_type,
                    start: dateObj + 'T'+starttimeObject,
                    end: dateObj + 'T'+endTime
                }
            );
        }
        setEventList(events)
    }, [props]);


    function renderEventContent(eventInfo) {
        return (
            <>
                <b>{eventInfo.timeText}</b>
                <i>{eventInfo.event.title}</i>
            </>
        )
    }

    const  handleDateClick = (eveargntInfo) => { // bind with an arrow function
        console.log(eveargntInfo)
    }

  /*  const handleWeekendsToggle = () => {
        setWeekendsVisible(!weekendsVisible)
    }*/

    const handleEventClick = (clickInfo) => {
        // console.log({clickInfo})
        const nikah_id = clickInfo.event._def.publicId
        const selectedNikah = props?.nikahs?.data.find(item => item.nikah_id == nikah_id);
        setNikahDetail([selectedNikah]);
        setShowDetail(true);
    }

    /*const handleEvents = (events) => {
        setcurrentEvents(events);
    }*/

   const handleDateSelect = (selectInfo) => {
       console.log({selectInfo});
       /* let title = prompt('Please enter a new title for your event')
        let calendarApi = selectInfo.view.calendar
        calendarApi.unselect() // clear date selection
        if (title) {
            calendarApi.addEvent({
                id: createEventId(),
                title,
                start: selectInfo.startStr,
                end: selectInfo.endStr,
                allDay: selectInfo.allDay
            })
        }*/
    }

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Events Calendar</h2>}
        >
            <Head title="Nikah Calender" />
            <div className="mb-6 block md:flex items-center justify-between">
                <h3 className="text-black text-[1.75rem] leading-7 font-product_sans_mediumregular">Events Calendar</h3>
            </div>
            <div className="at-calenderholder w-full">
                <FullCalendar
                    plugins={[ dayGridPlugin, timeGridPlugin, interactionPlugin, listPlugin]}
                    // dateClick={handleDateClick}
                    eventClick={handleEventClick}
                    initialView="dayGridMonth"
                    headerToolbar={{
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    }}
                    // weekends={false}
                    events={eventList}
                    // eventContent={renderEventContent}
                    // editable={true}
                    selectable={true}
                    // selectMirror={true}
                    // dayMaxEvents={true}
                    weekends={weekendsVisible}
                    // initialEvents={INITIAL_EVENTS} // alternatively, use the `events` setting to fetch from a feed
                    select={handleDateSelect}
                    // eventsSet={handleEvents} // called after events are initialized/added/changed/removed
                />
            </div>
            <EventDetail isOpen={showDetail} closeModal={closeModal} nikah={nikahDetail}/>
        </AuthenticatedLayout>
    );
}
