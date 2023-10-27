import React, {Fragment, useEffect, useState} from "react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import {Head, Link} from "@inertiajs/inertia-react";
import {HiEllipsisVertical} from "react-icons/hi2";
import {Dialog, Menu, Transition} from "@headlessui/react";
import AssignImamModal from "@/Components/AssignImamModal";
import AssignWalliModal from "@/Components/AssignWalli";
import AssignWitnessesModal from "@/Components/AssignWitnesses";
import ReactPaginate from "react-paginate";
import LoadingCircle from "@/Components/LoadingCircle";
import {toast} from "react-toastify";
import Select from "react-select";
import moment from "moment/moment";
import PrimaryButton from "@/Components/PrimaryButton";
import {Inertia} from "@inertiajs/inertia";
import { FaTrash} from "react-icons/fa";

export default function ShiftDetail(props) {
    const [shiftList, setShiftList] = useState([]);
    useEffect(() => {
        setShiftList(props.shift);
    }, [props]);

    const deleteSchedule = (e) => {
        axios.post(route('deleteScheduleDate'), {
            shift:shiftList,
        }).then(function (response) {
           if(response.status == 200){
               toast.success('Deleted successfully');
               props.closeModal();
               Inertia.reload();
           }

        }).catch(function (error) {

            // toast.error(response.data.message)
        });
    }

    return (
        <>
            <Transition appear show={props.isOpen} as={Fragment}>
                <Dialog
                    as="div"
                    className="relative z-10"
                    onClose={props.closeModal}
                >
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
                                    className="w-full max-w-[950px] transform rounded-2xl bg-white p-10 px-14 text-left align-middle shadow-xl transition-all">
                                    <Dialog.Title
                                        as="h3"
                                        className="text-xl text-center font-product_sansregular mb-8 text-black"
                                    >
                                        Event Details
                                    </Dialog.Title>
                                    <div className="w-full">
                                        <div className="at-ideasarea w-full">
                                            <div className="at-themetablearea at-ideastablearea w-full">
                                                <table className="at-themetable w-full">
                                                    <thead>
                                                    <tr>
                                                        <th className="font-product_sansbold">
                                                            Date
                                                        </th>
                                                        <th className="font-product_sansbold">
                                                            Start Time
                                                        </th>
                                                        <th className="font-product_sansbold">
                                                            End Time
                                                        </th>
                                                        <th className="font-product_sansbold">
                                                            Action
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td data-title="groom name">
                                                            <div
                                                                className="at-themeemployeinfo at-bdleftborder !min-h-[40px]">
                                                                <div className="at-usernameemail">
                                                                    <h3 className="text-black font-product_sansregular text-base leading-4 mb-0 tracking-wide">
                                                                        {new Date(shiftList?.start).toDateString()}
                                                                    </h3>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td data-title="bride name">
                                                            <div className="at-usernameemail">
                                                                <h3 className="text-black font-product_sansregular text-base leading-4 mb-0 tracking-wide">
                                                                    {shiftList?.start_time}
                                                                </h3>
                                                            </div>
                                                        </td>
                                                        <td data-title="type" className="text-left">
                                                            <span>{shiftList?.end_time}</span>
                                                        </td>
                                                        <td data-title="Time" className="text-left">

                                                            {/*<button type="button" className="inline-flex items-center justify-center  bglinear-gradient py-3 px-6 xl:py-4 xl:px-12 border border-transparent rounded-[10px] font-product_sansregular font-bold text-lg text-white capitalize tracking-widest active:bg-black transition ease-in-out duration-150"*/}
                                                            {/*onClick={(e) => deleteSchedule(e)}>*/}
                                                            {/*    Delete</button>*/}
                                                            <h4>
                                                                <FaTrash onClick={(e) => deleteSchedule(e)} />
                                                            </h4>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </Dialog.Panel>
                            </Transition.Child>
                        </div>
                    </div>
                </Dialog>
            </Transition>
        </>
    );
}
