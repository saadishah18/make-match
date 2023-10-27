import {Dialog, Transition} from '@headlessui/react'
import React, {Fragment, useState, useRef, useEffect} from 'react'
import DatePicker from "react-datepicker";
import "react-datepicker/dist/react-datepicker.css";
import PrimaryButton from "@/Components/PrimaryButton";
import moment from "moment";
import {toast} from "react-toastify";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-react";
import 'moment-timezone';


export default function CalendarModal(props) {
    const firstInputRef = useRef(null);
    const {auth} = usePage().props;
    // console.log(auth);
    const [startDate, setStartDate] = useState('');
    const [startTime, setStartTime] = useState(new Date());
    const [endTime, setEndTime] = useState(new Date());
    const [showDatePicker, setShowDatePicker] = useState(false);

    let handleColor = (time) => {
        return time.getHours() > 12 ? "text-success" : "text-error";
    };

    const formatInputValue = () => {
        if (!selectedDay) return '';
        return `Day: ${selectedDay.day}`;
    };

    function funcHandler() {
        props.isOpen(false)
        props.closeModal(false)
    }

    const submitHandler = (e) => {

        e.preventDefault();

        let start_date = moment(startDate).format('YYYY-MM-DD');


        if ( moment(startTime).isSame(moment(endTime))) {
            toast.error('Start and end time should be different');
            return false;
        }


        if ( moment(endTime).isBefore(moment(startTime))) {
            toast.error('End time should greater than start time');
            return false;
        }

        if (moment.duration(moment(endTime, 'HH:mm').diff(moment(startTime, 'HH:mm'))).asHours() > 8) {
            toast.error('The duration between start time and end time should not exceed 8 hours.');
            return false;
        }

        let utcStartDateTime =  moment(startDate).add(moment(startTime).format('HH:mm'));
        let utcEndDateTime =  moment(startDate).add(moment(endTime).format('HH:mm'));

        /*let start_time_utc = moment(utcStartDateTime).utc().format('HH:mm');
        let start_date_utc = moment(utcStartDateTime).utc().format('YYYY-MM-DD');
        let end_date_utc = moment(utcEndDateTime).utc().format('YYYY-MM-DD');
        let end_time_utc = moment(utcEndDateTime).utc().format('HH:mm');*/

        let start_date_time = moment(utcStartDateTime).format('YYYY-MM-DD HH:mm:ss');
        let end_date_time = moment(utcEndDateTime).format('YYYY-MM-DD HH:mm:ss');
        // let end_date_utc = moment.format('YYYY-MM-DD');
        // let end_time_utc = moment(utcEndDateTime).format('HH:mm');

        // console.log(start_date_utc, start_time_utc, end_date_utc, end_time_utc);
        axios.post(route('imam.store-timetable'), {
            start_date_time: start_date_time,
            end_date_time: end_date_time,
            // startTime: start_time_utc,
            // endTime: end_time_utc,
        }).then(function (response) {
            if (response.data.status == 200) {
                props.closeModal(false);
                toast.success(response.data.message);
                Inertia.reload();
            } else {
                props.closeModal(false);
                setLoader(false);
                toast.error('Something went wrong');
            }

        }).catch(function (error) {
            props.closeModal(false);
            console.log(error);
            // setLoader(false);
            // alert('error');

        });
    };
    const handleDatePciker = () => {
        // alert('here');
        setShowDatePicker(true)
    }
    return (
        <>
            <Transition appear show={props.isOpen} as={Fragment}>
                <Dialog as="div" className="relative z-10" onClose={props.closeModal}>
                    <Transition.Child
                        as={Fragment}
                        enter="ease-out duration-300"
                        enterFrom="opacity-0"
                        enterTo="opacity-100"
                        leave="ease-in duration-200"
                        leaveFrom="opacity-100"
                        leaveTo="opacity-0"
                    >
                        <div className="fixed inset-0 bg-black bg-opacity-25"/>
                    </Transition.Child>

                    <div className="fixed inset-0 overflow-y-auto">
                        <div className="flex min-h-full items-center justify-center p-4 text-center">
                            <Transition.Child
                                as={Fragment}
                                enter="ease-out duration-300"
                                enterFrom="opacity-0 scale-95"
                                enterTo="opacity-100 scale-100"
                                leave="ease-in duration-200"
                                leaveFrom="opacity-100 scale-100"
                                leaveTo="opacity-0 scale-95"
                            >
                                <Dialog.Panel
                                    className="w-full max-w-[460px] min-h-[325px] transform rounded-3xl bg-white py-8 px-10 text-left align-middle shadow-xl transition-all">
                                    <div className="w-full">

                                        {/*<form className="" onSubmit={submitHandler}>*/}
                                        <fieldset className="flex flex-col gap-8">
                                            <div>
                                                <input type="text" className="free-input"/>
                                                <label>Select Date</label>
                                                <DatePicker
                                                    id="my-calendar"
                                                    // showTimeSelect
                                                    selected={startDate}
                                                    onChange={(date) => setStartDate(date)}
                                                    // timeClassName={handleColor}
                                                    // timeZone="utc" // Set your desired time zone here
                                                    autoFocus={false}
                                                    minDate={moment().toDate()}
                                                />
                                            </div>

                                            <div className="flex flex-row gap-8 justify-center items-center">

                                                <div className="w-1/2">
                                                    <label>Start time</label>
                                                    <DatePicker
                                                        selected={startTime}
                                                        onChange={(date) => setStartTime(date)}
                                                        showTimeSelect
                                                        showTimeSelectOnly
                                                        timeIntervals={15}
                                                        timeCaption="Time"
                                                        dateFormat="hh:mm aa"
                                                        // timeZone="utc" // Set your desired time zone here

                                                    />
                                                </div>
                                                <div className="w-1/2">
                                                    <label>End time</label>
                                                    <DatePicker
                                                        selected={endTime}
                                                        onChange={(date) => setEndTime(date)}
                                                        showTimeSelect
                                                        showTimeSelectOnly
                                                        timeIntervals={15}
                                                        timeCaption="Time"
                                                        dateFormat="hh:mm aa"
                                                        // timeZone="utc" // Set your desired time zone here

                                                    />
                                                </div>
                                            </div>

                                            <div>
                                                {/* Remove Link After Backend */}
                                                {/*<Link className="w-full" href="/dashboardone">*/}
                                                <PrimaryButton
                                                    type="submit"
                                                    className="w-full gap-2"
                                                    onclick={submitHandler}
                                                >
                                                    Save
                                                    {/* Spinner Start */}
                                                    {/*{loading && <div className="lds-dual-ring"></div>}*/}
                                                </PrimaryButton>
                                                {/*</Link>*/}
                                            </div>
                                        </fieldset>
                                        {/*</form>*/}
                                    </div>
                                </Dialog.Panel>
                            </Transition.Child>
                        </div>
                    </div>
                </Dialog>
            </Transition>
        </>
    )
}
