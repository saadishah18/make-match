import React, {useEffect, useState} from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/inertia-react';
import {KhulaData} from "../../../../Data/Khula";
import AssignImamModal from "@/Components/AssignImamModal";
import TalaqCertificateModal from "@/Components/TalaqCertificateModal";
import KhulaCertificateModal from "@/Components/KhulaCertificateModal";
import ReactPaginate from "react-paginate";
import {toUpper} from "lodash";

export default function Khula(props) {
    const [khuluList, setKhuluList] = useState([]);
    let [isOpen, setIsOpen] = useState(false)
    let [isOpenOne, setIsOpenOne] = useState(false)
    const [loader, setLoader] = useState(true);
    const [detail, setDetail] = useState(true);

    useEffect(() => {
        setKhuluList(props.khulus.data)
        setLoader(false);
    }, [props]);

    function closeModal() {
        setIsOpen(false)
    }

    function openModal(item) {
        setDetail(item);
        setIsOpen(true)
    }

    function closeModalOne() {
        setIsOpenOne(false)
    }

    function openModalOne() {
        setIsOpenOne(true)
        console.log("abc")
    }

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Khula Management</h2>}
        >
            <Head title="Imam Khula Management" />
            <div className="at-pagehead mb-6 block md:flex items-center justify-between">
                <h3 className="text-black text-[1.75rem] leading-7 font-product_sans_mediumregular">Khula Management</h3>
                <form className="at-searchform min-w-[370px]" onSubmit={e => e.preventDefault()}>
                    <fieldset className="">
                        <div className="form-group relative">
                            <input
                                className="h-[50px] rounded-lg border pr-[40px] border-bordercolor text-black placeholder:text-gray1 focus:border-black focus:ring-0"
                                type="text"
                                name="search"
                                placeholder="Search"
                            />
                            <svg
                                className="absolute top-1/2 -translate-y-1/2 right-3 pointer-events-none"
                                width="20"
                                height="20"
                                viewBox="0 0 21 21"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fillRule="evenodd"
                                    clipRule="evenodd"
                                    d="M9 1.75C4.99594 1.75 1.75 4.99594 1.75 9C1.75 13.0041 4.99594 16.25 9 16.25C13.0041 16.25 16.25 13.0041 16.25 9C16.25 4.99594 13.0041 1.75 9 1.75ZM0.25 9C0.25 4.16751 4.16751 0.25 9 0.25C13.8325 0.25 17.75 4.16751 17.75 9C17.75 11.1462 16.9773 13.112 15.6949 14.6342L20.5303 19.4697C20.8232 19.7626 20.8232 20.2374 20.5303 20.5303C20.2374 20.8232 19.7626 20.8232 19.4697 20.5303L14.6342 15.6949C13.112 16.9773 11.1462 17.75 9 17.75C4.16751 17.75 0.25 13.8325 0.25 9Z"
                                    fill="#C0BCBC"
                                />
                            </svg>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div className="at-ideasarea w-full">
                <div className="at-themetablearea at-ideastablearea w-full">
                    <table className="at-themetable">
                        <thead>
                        <tr>
                            <th className="font-product_sansbold !px-5">Requester</th>
                            <th className="font-product_sansbold !px-5">Khula From</th>
                            <th className="font-product_sansbold !px-5">Date</th>
                            <th className="font-product_sansbold !px-5">Reason</th>
                            <th className="font-product_sansbold !px-5">Status</th>
                            <th className="!text-center font-product_sansbold !px-5">Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        {khuluList.length ? khuluList.map((item, i) => (
                            <tr key={i}>
                                <td data-title="requester" className="text-left !px-5 !py-3">
                                    <span> <a href={`mailto:${item.requester_email}`}>
                                                    <img
                                                        className="w-[40px] p-0 inline-block"
                                                        width="50px"
                                                        src="/assets/images/svg/email-envelope.svg"
                                                        alt="Envelope"
                                                    />
                                        &nbsp;&nbsp;
                                        {item.requester}
                                                </a>
                                                </span>

                                </td>
                                <td data-title="groom" className="text-left !px-5 !py-3">
                                    <span> <a href={`mailto:${item.groom_email}`}>
                                                    <img
                                                        className="w-[40px] p-0 inline-block"
                                                        width="50px"
                                                        src="/assets/images/svg/email-envelope.svg"
                                                        alt="Envelope"
                                                    />
                                        &nbsp;&nbsp;
                                        {item.groom}
                                                </a>
                                                </span>

                                </td>
                                <td data-title="date" className="text-left !px-5 !py-3">
                                    <span>{item.khula_date}</span>
                                </td>
                                <td data-title="reason" className="text-left !px-5 !py-3">
                                    <span>{item.second_khulu_reason == null ?  item.reason : item.second_khulu_reason}</span>
                                </td>
                                <td data-title="status" className="text-left !px-5 !py-3">
                                    <span>{ item.second_khulu_status != null ? toUpper(item.second_khulu_status) : toUpper(item.first_khulu_status) }</span>
                                </td>
                                <td data-title="Action" className="!px-5 !py-3">
                                    {
                                      item.is_validated == 0 && ((item.first_khulu_status == 'requested' || item.second_khulu_status == 'completed')
                                            || (item.first_khulu_status == 'completed' || item.second_khulu_status == 'requested')
                                      ) ?
                                            <>
                                                <div className="mx-auto flex justify-center items-center">
                                                    <button
                                                        type="button"
                                                        className="w-[40px] h-[40px] rounded-[10px] bg-deletecolor bg-opacity-10 mr-3 flex justify-center items-center"
                                                        onClick={() => openModal(item) }
                                                    >
                                                        <span className="at-themetoolip">View</span>
                                                        <img src='/assets/images/svg/show-pass.svg' alt="View Icon"/>
                                                    </button>
                                            {/* <button
                                            type="button"
                                            className="w-[40px] h-[40px] rounded-[10px] bg-[#e6fafe] flex justify-center items-center"
                                        >
                                                <span className="at-themetoolip">Email</span>
                                                <img src='/assets/images/svg/email-envelope.svg' alt="View Icon"/>
                                        </button>*/}
                                                </div>
                                            </> : 'N / A'
                                    }
                                </td>
                            </tr>
                        )) : <tr>
                            <td colSpan={6}>
                                <div className="flex justify-center items-center min-h-[650px]">
                                    <img
                                        src="/assets/images/nodata-found.png"
                                        alt="no data found"
                                    />
                                </div>
                            </td>
                        </tr>}
                        </tbody>
                    </table>
                   {/* <ReactPaginate
                        breakLabel="..."
                        nextLabel=">"
                        pageRangeDisplayed={5}
                        previousLabel="<"
                        renderOnZeroPageCount={null}
                        className="at-pagenation"
                    />*/}
                </div>
            </div>
            <KhulaCertificateModal isOpen={isOpen} closeModal={closeModal} khulu={detail}/>
        </AuthenticatedLayout>
    );
}
