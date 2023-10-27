import React, {useEffect, useState} from 'react';
import FullCalendar, {formatDate} from '@fullcalendar/react'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import {Head, Link, usePage} from '@inertiajs/inertia-react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import moment from "moment";
import PrimaryButton from "@/Components/PrimaryButton";
import CalendarModal from "@/Components/CalendarModal";
import ShiftDetail from "@/Components/ShiftDetail";
import 'moment-timezone';

export default function EventsCalendar(props) {
    const {auth} = usePage().props;
    const [eventList, setEventList] = useState([]);
    const [showDetail, setShowDetail] = useState(false);
    const [isCreate, setIsCreate] = useState(false);
    const [currentEvents, setcurrentEvents] = useState([]);
    const [shiftDetail, setShiftDetail] = useState([]);

    function closeCreateModal() {
        setIsCreate(false);
    }
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

    const openCreateTime = (event) => {
        event.stopPropagation();
        setIsCreate(true);
        // alert('gj');
    }

    function closeModal() {
        setShowDetail(false)
    }

    useEffect(() => {
        // setLoader(true)
        let events = [];
        let starttimeObject = '';
        let endTime = ''
        let count = 0;

        if(Object.keys(props.timetable).length){
            if (props?.timetable.shift_dates?.start_date_time?.length) {
                for (let index in props?.timetable?.shift_dates.start_date_time) {
                    let start_date_time = props?.timetable?.shift_dates.start_date_time[index];
                    let end_date_time = props?.timetable?.shift_dates.end_date_time[index];

                    events.push({
                        count: count++,
                        // id: props?.timetable.id,
                        title: 'Imam Scheduler',
                        start: start_date_time,
                        end: end_date_time,
                        shift_date: start_date_time,
                        start_time: moment(start_date_time).format('hh:mm A'),
                        end_time: moment(end_date_time).format('hh:mm A'),
                    });
                    // console.log({'event':events});
                }
            }
        }
        setEventList(events)
    }, [props]);

 /*  const handleDateClick = (arg) => { // bind with an arrow function

       let selected_date = arg.dateStr;
       const selected_shift = eventList.find(item => item.shift_date == selected_date);

       if(typeof selected_shift !== 'undefined'){
           setShiftDetail(selected_shift);
           setShowDetail(true);
       }
   }*/

    const handleEventClick = async (clickInfo) => {
        const time_table_id = clickInfo.event._def.extendedProps.count;

        const selected_shift = eventList.find(item => item.count == time_table_id);
        if(typeof selected_shift !== 'undefined'){
           await setShiftDetail(selected_shift);
            setShowDetail(true);
        }
    }

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Events Calendar</h2>}
        >
            <Head title="Imam Scheduler"/>
            <div className="mb-6 block md:flex items-center justify-between">
                <h3 className="text-black text-[1.75rem] leading-7 font-product_sans_mediumregular">Events Calendar</h3>
                <PrimaryButton
                    type="button"
                    onclick={openCreateTime}

                >
                    Create
                </PrimaryButton>
            </div>
            <div className="at-calenderholder w-full">
                <FullCalendar
                    plugins={[dayGridPlugin, timeGridPlugin, interactionPlugin, listPlugin]}
                    initialView="dayGridMonth"
                    headerToolbar={{
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    }}
                    events={eventList}
                    weekends={true}
                    // dateClick={handleDateClick}
                    eventClick={handleEventClick}
                    // select={handleDateSelect}
                />

            </div>
            <CalendarModal isOpen={isCreate} closeModal={closeCreateModal}/>
            {
                shiftDetail && <ShiftDetail isOpen={showDetail} closeModal={closeModal} shift={shiftDetail}/>
            }


        </AuthenticatedLayout>
    );
}
