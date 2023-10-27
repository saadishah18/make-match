import React, { Fragment, useEffect, useState } from "react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link } from "@inertiajs/inertia-react";
import { HiEllipsisVertical } from "react-icons/hi2";
import { Dialog, Menu, Transition } from "@headlessui/react";
import AssignImamModal from "@/Components/AssignImamModal";
import AssignWalliModal from "@/Components/AssignWalli";
import AssignWitnessesModal from "@/Components/AssignWitnesses";
import ReactPaginate from "react-paginate";
import LoadingCircle from "@/Components/LoadingCircle";
import { toast } from "react-toastify";
import Select from "react-select";
import moment from "moment/moment";

export default function EventDetail(props) {
    const [nikahList, setNikahList] = useState([]);

    useEffect(() => {
        setNikahList(props.nikah);
    }, [props]);

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
                                <Dialog.Panel className="w-full max-w-[950px] transform rounded-2xl bg-white p-10 px-14 text-left align-middle shadow-xl transition-all">
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
                                                                Groom
                                                            </th>
                                                            <th className="font-product_sansbold">
                                                                Bride
                                                            </th>
                                                            <th className="font-product_sansbold">
                                                                Category
                                                            </th>
                                                            <th className="font-product_sansbold">
                                                                Time
                                                            </th>
                                                            <th className="font-product_sansbold">
                                                                Date
                                                            </th>
                                                            <th className="font-product_sansbold">
                                                                Status
                                                            </th>
                                                            <th className="font-product_sansbold">
                                                                Assigned Imam
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {nikahList.map(
                                                            (item, i) => (
                                                                <tr key={i}>
                                                                    <td data-title="groom name">
                                                                        <div className="at-themeemployeinfo at-bdleftborder !min-h-[40px]">
                                                                            <div className="at-usernameemail">
                                                                                <h3 className="text-black font-product_sansregular text-base leading-4 mb-0 tracking-wide">
                                                                                    {item.groom}
                                                                                </h3>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td data-title="bride name">
                                                                        <div className="at-usernameemail">
                                                                            <h3 className="text-black font-product_sansregular text-base leading-4 mb-0 tracking-wide">
                                                                                {item.bride}
                                                                            </h3>
                                                                        </div>
                                                                    </td>
                                                                    <td
                                                                        data-title="type"
                                                                        className="text-left"
                                                                    >
                                                                        <span>
                                                                            {item.nikah_type}
                                                                        </span>
                                                                    </td>
                                                                    <td
                                                                        data-title="Time"
                                                                        className="text-left"
                                                                    >
                                                                        <span>
                                                                            {moment(item.start_time_simple).format("hh:mm A")}
                                                                        </span>
                                                                    </td>
                                                                    <td
                                                                        data-title="Nikah Date"
                                                                        className="text-left"
                                                                    >
                                                                        <span>
                                                                            {item.start_date}
                                                                        </span>
                                                                    </td>
                                                                    <td data-title="Status">
                                                                        <span
                                                                            className={`at-empstatus flex min-w-[100px] max-w-[100px] rounded-[8px] h-10 items-center justify-center text-base leading-4 text-black font-product-sansregular ${
                                                                                item.is_validated == 0 ? "at-bgcolorpending" : "at-bgrated"
                                                                            }`}
                                                                        >
                                                                            {
                                                                               item.is_validated == 0 ? "Pending" : 'Completed'
                                                                            }
                                                                        </span>
                                                                    </td>
                                                                    <td
                                                                        data-title="Imam Name"
                                                                        className="text-left"
                                                                    >
                                                                        <span>
                                                                            {
                                                                                item.assigned_imam
                                                                            }
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                            )
                                                        )}
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
