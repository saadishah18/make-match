import { Dialog, Transition } from '@headlessui/react'
import { Fragment, useState } from 'react'
import moment from 'moment';
import {toast} from "react-toastify";

export default function ZoomMeetingModal(props) {
    const nikah = props.nikah
    function funcHandler(){
        props.handler()
        props.closeModal()
    }

    const handleZoomMeeting = () => {
        let now = moment(); // Use local time
        let nikah_date = moment(nikah.nikah_date); // Use local time for nikah date
        console.log(nikah_date,now, now.isBefore(nikah_date,'Date'),now.isAfter(nikah_date,'Date'), now.isSame(nikah_date,'Date'));

        if (now.isBefore(nikah_date,'Date')) {
            let formattedDate = nikah_date.format('MMMM D, YYYY h:mm A'); // Format nikah date for display
            toast.error(`Nikah Date or local time (${formattedDate}) has not arrived yet`);
            return;
        }
        else if(now.isAfter(nikah_date,'Date')) {
            toast.error('Nikah Date or UTC Format time has been passed');
            return;
        }else if(now.isSame(nikah_date,'Date')){
              window.location.href = nikah.zoom_start_url;
        }
        return;


        let start_time = moment(nikah.start_time_simple,'hh:mm:ss').format('hh:mm')
        let current_time = now.utc().format('hh:mm');


       /* if (now.isBefore(nikah_date,'Date') || now.isSame(nikah_date, 'day') || current_time < start_time) {
             toast.error('Nikah Date or UTC Format time not arrived yet');
             return
         }
         else if(now.isAfter(nikah_date,'Date') || current_time > start_time) {
             toast.error('Nikah Date or UTC Format time has been passed');
             return;
         }*/


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
                        <div className="fixed inset-0 bg-black bg-opacity-25" />
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
                                <Dialog.Panel className="w-full max-w-[550px] flex items-center justify-center transform overflow-hidden rounded-2xl bg-white p-10 px-14 text-left align-middle shadow-xl transition-all">
                                    <div className="w-75 flex items-center justify-center flex-col">
                                        <h4 className="text-[#000] font-product_sansregular text-xl mt-7 mb-2">
                                            Click on button to start meeting
                                        </h4>
                                        <div className="w-full float-left mt-11 flex items-center justify-center gap-3">
                                            <button
                                                type="button"
                                                className="min-h-[60px] text-lg font-product_sansregular font-bold tracking-wider border-[2px] border-gray1 rounded-[10px] min-w-[168px] bg-white"
                                                onClick={props.closeModal}
                                            >
                                                Cancel
                                            </button>
                                            <button
                                                type="button"
                                                className="min-h-[60px] text-lg font-product_sansregular font-bold tracking-widest rounded-[10px] min-w-[168px] px-5 text-white bglinear-gradient"
                                                onClick={handleZoomMeeting}
                                            >
                                                Start Zoom Meeting
                                            </button>
                                            {/*<a href={nikah?.zoom_start_url} target="_blank" rel="noopener noreferer"
                                               className="min-h-[60px] text-lg font-product_sansregular font-bold tracking-widest rounded-[10px]
                                                   min-w-[168px] px-5 text-white bglinear-gradient">
                                                Start Zoom Meeting
                                            </a>*/}
                                        </div>
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
