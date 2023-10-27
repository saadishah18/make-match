import React, {useEffect, useState} from 'react';
import {Head} from '@inertiajs/inertia-react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Listbox} from '@headlessui/react';
import {HiChevronDown, HiCheck} from "react-icons/hi";
import {lowerCase} from "lodash";
import Select from 'react-select'
import '@hassanmojab/react-modern-calendar-datepicker/lib/DatePicker.css';
import {Calendar, utils} from "@hassanmojab/react-modern-calendar-datepicker";
import moment from "moment";
import axios from "axios";
import LoadingCircle from "@/Components/LoadingCircle";
import {toast} from "react-toastify";


let Days = [
    {value: 'monday', label: 'Monday', id: '1'},
    {value: 'tuesday', label: 'Tuesday', id: '2'},
    {value: 'wednesday', label: 'Wednesday', id: '3'},
    {value: 'thursday', label: 'Thursday', id: '4'},
    {value: 'friday', label: 'Friday', id: '5'},
    {value: 'saturday', label: 'Saturday', id: '6'},
    {value: 'sunday', label: 'Sunday', id: '7'},
]

const ofDays = [
    {value: 'monday', label: 'Monday', id: '1'},
    {value: 'tuesday', label: 'Tuesday', id: '2'},
    {value: 'wednesday', label: 'Wednesday', id: '3'},
    {value: 'thursday', label: 'Thursday', id: '4'},
    {value: 'friday', label: 'Friday', id: '5'},
    {value: 'saturday', label: 'Saturday', id: '6'},
    {value: 'sunday', label: 'Sunday', id: '7'},
]

const Time = [{value: '08:00 AM - 08:00 PM', label: '08:00 AM - 08:00 PM', id: '1'}]

export default function EventsCalendar(props) {

    const [selectedDaysOptions, setSelectedDaysOptions] = useState([]);
    const [selectedDays, setSelectedDays] = useState([]);
    const [selectedOffDaysOptions, setSelectedOffDaysOptions] = useState([]);
    const [selectedOffDays, setSelectedOffDays] = useState([]);
    const [selectedOnDates, setSelectedOnDates] = useState([]);
    const [selectedOfDates, setSelectedOfDates] = useState([]);
    const [selectedTime, setSelectedTime] = useState('');
    const [loader, setLoader] = useState(false);


    useEffect(() => {
        // const var;
        if ('on_days' in props.timetable) {
            let onDaysOptions = [];
            let onDays = [];
            Object.keys(props.timetable.on_days).forEach(function (key) {
                onDaysOptions.push({
                    value: lowerCase(props.timetable.on_days[key]),
                    label: props.timetable.on_days[key]
                });
                onDays.push(props.timetable.on_days[key]);
            });
            setSelectedDaysOptions(onDaysOptions);
            setSelectedDays(onDays);

            let choosedOffDays = [];
            let ofdays = [];
            Object.keys(props.timetable.off_days).forEach(function (key) {
                choosedOffDays.push({
                    value: lowerCase(props.timetable.off_days[key]),
                    label: props.timetable.off_days[key]
                });
                ofdays.push(props.timetable.off_days[key]);
            });
            setSelectedOffDaysOptions(choosedOffDays);
            setSelectedOffDays(ofdays);

            let choosedOnDates = [];
            Object.keys(props.timetable.on_dates).forEach(function (key) {
                let date = moment(props.timetable.on_dates[key]);
                choosedOnDates.push({
                    day: parseInt(date.format('DD')),
                    month: Number(date.format('MM')),
                    year: parseInt(date.format('YYYY')),
                });
            });
            setSelectedOnDates(choosedOnDates);

            let choosedOffDates = [];
            Object.keys(props.timetable.off_dates).forEach(function (key) {

                let date = moment(props.timetable.off_dates[key]);
                choosedOffDates.push({
                    day: parseInt(date.format('DD')),
                    month: Number(date.format('MM')),
                    year: parseInt(date.format('YYYY')),
                });
            });
            setSelectedOfDates(choosedOffDates);
            setSelectedTime({
                value: props.timetable.shift_time.start_time+' - '+props.timetable.shift_time.end_time,
                label: props.timetable.shift_time.start_time+' - '+props.timetable.shift_time.end_time
            })
        }
        // setLoader(false);
    }, [props]);

    const resetForm = () => {
        setSelectedDaysOptions([]);
        setSelectedOffDaysOptions([]);
        setSelectedOnDates([]);
        setSelectedOfDates([]);
        setSelectedDays([]);
        setSelectedOffDays([]);
        setSelectedTime([]);
    }

    const onDaysChangeHandler = (e) => {
        setSelectedDaysOptions(e.value);
        let temp_days = [];
        for (const day of e) {
            temp_days.push(day.label)
        }
        setSelectedDays(temp_days);
    }

    const offDaysChangeHandler = (e) => {
        setSelectedOffDaysOptions(e.value);
        let temp_days = [];
        for (const day of e) {
            temp_days.push(day.label)
        }
        setSelectedOffDays(temp_days);
    }

    const updateTimeTable = () => {

        if(selectedDays.length == 0 || selectedTime.length == 0 || selectedOnDates.length == 0 || selectedOfDates.length == 0){
            toast.error('Please fill all fields');
            return false;
        }

        if (selectedDays.length == 0) {
            // alert('here');
            let temp_days = [];
            for (const day of selectedDaysOptions) {
                temp_days.push(day.label)
            }
            setSelectedDays(temp_days);
        }

        if (selectedOffDays.length == 0) {
            let of_days = [];
            for (const day of selectedOffDaysOptions) {
                of_days.push(day.label)
            }
            setSelectedOffDays(of_days);
        }

        let on_dates = [];

        for (const date of selectedOnDates) {
            // console.log(date);
            console.log('date', `${date.year}-${date.month}-${date.day}`);
            on_dates.push(moment(`${date.year}-${date.month}-${date.day}`).format('YYYY-MM-DD'));
        }

        let choosed_of_dates = [];

        for (const date1 of selectedOfDates) {
            choosed_of_dates.push(moment(`${date1.year}-${date1.month}-${date1.day}`).format('YYYY-MM-DD'));
        }

        let shift_time = Time[0].value;

        setLoader(true);

        axios.post(route('imam.store-timetable'), {
            onDays: selectedDays,
            offDays: selectedOffDays,
            onDates: on_dates,
            offDates: choosed_of_dates,
            shiftTime: shift_time,
        }).then(function (response) {
            // console.log(response.status);
             if(response.status == 200){
                 setLoader(false);
                 console.log(response.data.message)
                 toast.success(response.data.message);

             }else{
                 setLoader(false);
                 toast.error(response.data.message);
             }

        }).catch(function (error) {
                console.log({error});
                setLoader(false);
                toast.error(error.data.message);
                /*  props.closeModal(false);
                  console.log(error);
                  setLoader(false);*/

            });
    }

    const handleShitTimeChange = (e) => {
        console.log(e.value);
        setSelectedTime(e.value);
    }

    // console.log({selectedOnDates})

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Event Schedule</h2>}
        >
            <Head title="Imam Schedular"/>
            {
                loader ? <LoadingCircle loading={loader} /> : (
                    <>
                        <div className="mb-6 block md:flex items-center justify-between">
                            <h3 className="text-black text-[1.75rem] leading-7 font-product_sans_mediumregular">Event Schedule</h3>
                        </div>

                        <div className="at-eventschedule w-full">
                            <form className="w-full">
                                <fieldset className="w-full">
                                    <div className="grid grid-cols-2 gap-5">
                                        <div>
                                            <label className="block text-sm text-black font-bold font-product_sansregular mb-2">
                                                On days
                                            </label>
                                            <Select placeholder="Select On Days" options={Days} name="on_days" isMulti
                                                    className="basic-multi-select rt-select"
                                                // menuIsOpen="true"
                                                    classNamePrefix="select"
                                                    value={selectedDaysOptions}
                                                /*  onChange={(e) => {
                                                      selectedDaysOptions.push(e.value);
                                                  }}*/
                                                    onChange={onDaysChangeHandler}
                                            />
                                        </div>
                                        <div>
                                            <label className="block text-sm text-black font-bold font-product_sansregular mb-2">Off
                                                Days</label>
                                            <Select value={selectedOffDaysOptions} placeholder="Select Off days" options={ofDays}
                                                    name="off_days" isMulti
                                                    className=" basic-multi-select rt-select"
                                                    classNamePrefix="select"
                                                    onChange={offDaysChangeHandler}
                                            />
                                        </div>
                                        <div>
                                            <label className="block text-sm text-black font-bold font-product_sansregular mb-2">
                                                on Dates
                                            </label>
                                            <Calendar
                                                value={selectedOnDates}
                                                onChange={setSelectedOnDates}
                                                shouldHighlightWeekends
                                                name="on_dates"
                                                // minimumDate={moment().format('YYYY-MM-DD')}
                                                minimumDate={utils().getToday()}
                                                disabledDays={selectedOfDates}
                                                calendarClassName="responsive-calendar"
                                            />
                                        </div>
                                        <div>
                                            <label className="block text-sm text-black font-bold font-product_sansregular mb-2">
                                                Off Dates
                                            </label>
                                            <Calendar
                                                value={selectedOfDates}
                                                onChange={setSelectedOfDates}
                                                shouldHighlightWeekends
                                                disabledDays={selectedOnDates}
                                                calendarClassName="responsive-calendar"
                                                minimumDate={utils().getToday()}
                                            />
                                        </div>
                                        <div>
                                            <label className="block text-sm text-black font-bold font-product_sansregular mb-2">
                                                Shift Time
                                            </label>
                                            <Select defaultValue={selectedTime} placeholder="Select Shift Time" options={Time}
                                                name="shift_time"
                                                className=" basic-multi-select"

                                                classNamePrefix="select"
                                                onChange={(e) => handleShitTimeChange(e)}
                                            />
                                        </div>
                                    </div>
                                </fieldset>
                                <div className="w-full float-left mt-11 flex items-center gap-5">
                                    <button
                                        onClick={resetForm}
                                        type="button"
                                        className="min-h-[60px] text-lg font-product_sansregular font-bold tracking-wider border-[2px] border-gray1 rounded-[10px] min-w-[168px] bg-white"
                                    >
                                        Reset
                                    </button>
                                    <button
                                        onClick={updateTimeTable}
                                        type="button"
                                        className="min-h-[60px] text-lg font-product_sansregular font-bold tracking-widest rounded-[10px] min-w-[168px] px-5 text-white bglinear-gradient"
                                    >
                                        Save Time Settings
                                    </button>
                                </div>
                            </form>

                        </div>
                    </>
                )
            }

        </AuthenticatedLayout>
    );
}
