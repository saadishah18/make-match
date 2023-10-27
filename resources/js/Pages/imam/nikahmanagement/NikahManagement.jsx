import React, {Fragment, useEffect, useState} from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, Link, usePage} from '@inertiajs/inertia-react';
import {NikahData} from "../../../../Data/NikahData";
import { HiEllipsisVertical } from "react-icons/hi2";
import {Menu, Transition} from '@headlessui/react'
import ZoomMeetingModal from "@/Components/ZoomMeetingModal";
import UploadCerificateModal from "@/Components/UploadCertificateModal";
import AddZoomLinkModal from "@/Components/AddZoomLinkModal";
import ValidateCertificateModal from "@/Components/ValidateCertificateModal";
import ReactPaginate from "react-paginate";
import LoadingCircle from "@/Components/LoadingCircle";
import {toast} from "react-toastify";
import moment from 'moment';
export default function NikahManagement(props) {
    const {flash} = usePage().props;
    let [isOpenZoomModal, setIsOpenZoomModal] = useState(false)
    let [isOpenTwo, setIsOpenTwo] = useState(false)
    const [loader, setLoader] = useState(true);
    const [nikahList, setNikahList] = useState([]);
    let [isOpenThree, setIsOpenThree] = useState(false)
    let [openValidateModal, setOpenValidateModal] = useState(false)
    const [nikahDetail, setNikahDetail] = useState({});

    useEffect(() => {
        setNikahList(props.nikahs.data);
        setLoader(false);
    }, [props]);

    function closeZoomModal() {
        setIsOpenZoomModal(false)
    }

    function openZooCallModal(detail) {
        setNikahDetail(detail);
        setIsOpenZoomModal(true)
    }

    function closeModalTwo() {
        setIsOpenTwo(false)
    }

    function openModalTwo(detail) {
        setNikahDetail(detail);
        setIsOpenTwo(true)
    }

    function closeModalThree() {
        setIsOpenThree(false)
    }

    function openModalThree(detail) {
        if(detail.assingned_witness == ''){
            toast.info('Witnesses not assigned to this nikah. Please Contact Admin!')
            return false;
        }
        setNikahDetail(detail);
        setIsOpenThree(true)
    }

    function closeCertificateModal() {
        setOpenValidateModal(false)
    }

    function openValidateCertificateModal(detail) {
        // console.log({detail});
        let now = moment(); // Use local time
        let nikah_date = moment(detail.nikah_date); // Use local time for nikah date
        console.log(nikah_date,now, now.isBefore(nikah_date,'Date'),now.isAfter(nikah_date,'Date'), now.isSame(nikah_date,'Date'));

        if (now.isBefore(nikah_date,'Date')) {
            let formattedDate = nikah_date.format('MMMM D, YYYY h:mm A'); // Format nikah date for display
            toast.error(`Nikah Date or local time (${formattedDate}) has not arrived yet`);
            return;
        }

        if(detail.assingned_witness == ''){
            toast.info('Witnesses not assigned to this nikah. Please Contact Admin!')
            return false;
        }
        setNikahDetail(detail);
        setOpenValidateModal(true);
    }

    useEffect(() => {
        if (flash.message) {
            toast.error(flash.message);
            flash.message = '';
        }
        if (flash.success) {
            toast.success(flash.success);
            flash.success = '';
        }
        if (flash.error) {
            toast.error(flash.error);
            flash.error = '';
        }
    }, [flash])

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Ideas</h2>}
        >

            <Head title="Imam Nikah Management" />
            {
                loader ? <LoadingCircle loading={loader} /> : (
                 <>
                     <div className="at-pagehead mb-6 block md:flex items-center justify-between">
                         <div className="flex flex-col">
                             <h3 className="text-black text-[1.75rem] leading-7 font-product_sans_mediumregular">Nikah Management</h3>
                             <span className="block font-medium mt-2"> Total Count: (20)</span>
                         </div>
                         <form className="at-searchform min-w-[370px]">
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
                     <div className="at-ideasarea w-full flex">
                         <div className="at-themetablearea at-ideastablearea w-full grow">
                             <table className="at-themetable">
                                 <thead>
                                 <tr>
                                     <th className="font-product_sansbold">Groom</th>
                                     <th className="font-product_sansbold">Bride</th>
                                     <th className="font-product_sansbold">Category</th>
                                     <th className="font-product_sansbold">Time</th>
                                     <th className="font-product_sansbold">Date</th>
                                     <th className="font-product_sansbold">Status</th>
                                     <th className="!text-center font-product_sansbold">Action</th>
                                 </tr>
                                 </thead>
                                 <tbody>
                                 { nikahList.length > 0 ? nikahList.map((item, i) => (
                                     <tr key={i+1}>
                                         <td data-title="Employee">
                                             <div className="at-themeemployeinfo at-bdleftborder">
                                                 <div className="at-usernameemail">
                                                     <h3 className="text-black font-product_sansregular text-base leading-4 mb-2 tracking-wide">
                                                         {item.groom}
                                                     </h3>
                                                 </div>
                                             </div>
                                         </td>
                                         <td data-title="Employee">
                                             <div className="at-usernameemail">
                                                 <h3 className="text-black font-product_sansregular text-base leading-4 mb-2 tracking-wide">{item.bride}</h3>
                                             </div>
                                         </td>
                                         <td data-title="Designation" className="text-left">
                                             <span>{item.nikah_type}</span>
                                         </td>
                                         <td data-title="Department" className="text-left">
                                             <span>{item?.start_time}</span>
                                         </td>
                                         <td data-title="Date" className="text-left">
                                             <span>{item.start_date}</span>
                                         </td>
                                         <td data-title="Title">
                                   <span
                                       className={`at-empstatus flex min-w-[100px] max-w-[100px] rounded-[8px] h-10 items-center justify-center text-base leading-4 text-black font-product-sansregular ${
                                           item.is_validated == 0 ? 'at-bgcolorpending' : 'at-bgrated'
                                       }`}
                                   >
                                      {item.is_validated == 0 ? 'Pending' : 'Completed'}
                                   </span>
                                         </td>
                                         <td data-title="Action" className="px-2">
                                             <Menu as="div" className="relative inline-block text-left">
                                                 <Menu.Button className="bg-themecolor text-white text-[30px] rounded-lg font-product_sansregular px-[6px] min-h-[40px] flex items-center justify-center mx-auto">
                                                     <HiEllipsisVertical />
                                                 </Menu.Button>
                                                 <Transition
                                                     as={Fragment}
                                                     enter="transition ease-out duration-100"
                                                     enterFrom="transform opacity-0 scale-95"
                                                     enterTo="transform opacity-100 scale-100"
                                                     leave="transition ease-in duration-75"
                                                     leaveFrom="transform opacity-100 scale-100"
                                                     leaveTo="transform opacity-0 scale-95"
                                                 >

                                                     <Menu.Items className="absolute z-10 right-0 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                                         {
                                                             // moment(item.nikah_date).format('YYYY-MM-DD') == moment().format('YYYY-MM-DD') ?
                                                                 <>
                                                                     <Menu.Item>
                                                                         <button
                                                                             type="button"
                                                                             className="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                                                             onClick={() => openZooCallModal(item)}
                                                                         >
                                                                             Start Ceremony
                                                                         </button>
                                                                     </Menu.Item>
                                                                 </>
                                                         }

                                                         <Menu.Item>
                                                             <button type="button" className="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                                                     onClick={() => openModalThree(item)}
                                                             >
                                                                 Zoom Link
                                                             </button>
                                                         </Menu.Item>
                                                         {
                                                             item.services.some(service => service.slug === 'print') &&  item.is_validated == 1 ?
                                                             <>
                                                                 <Menu.Item>
                                                                     <button type="button" className="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                                                             onClick={() => openModalTwo(item)}
                                                                     >
                                                                         Upload Certificate
                                                                     </button>
                                                                 </Menu.Item>
                                                             </> : ''
                                                         }
                                                         {
                                                             item.is_validated == 0 ?
                                                                 <>
                                                                     <Menu.Item>
                                                                         <button type="button" className="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                                                                 onClick={ () => openValidateCertificateModal(item)}
                                                                         >
                                                                             Validate
                                                                         </button>
                                                                     </Menu.Item>
                                                                 </>
                                                             : ''
                                                         }

                                                         <Menu.Item>
                                                             <Link href={route('imam.nikahdetails',[item.nikah_id])} className="!border-t !border-t-gray-100">
                                                                 <button type="button" className="block w-full px-4 cursor-pointer py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                                                     View Details
                                                                 </button>
                                                             </Link>
                                                         </Menu.Item>
                                                     </Menu.Items>
                                                 </Transition>
                                             </Menu>
                                         </td>
                                     </tr>
                                 )) :
                                     <tr>
                                         <td colSpan={4}>
                                             <div className="flex justify-center items-center min-h-[650px]">
                                                 <img
                                                     src="/assets/images/nodata-found.png"
                                                     alt="no data found"
                                                 />
                                             </div>
                                         </td>
                                     </tr>

                                 }
                                 </tbody>
                             </table>
                             <ReactPaginate
                                 breakLabel="..."
                                 nextLabel=">"
                                 // onPageChange={handlePageClick}
                                 pageRangeDisplayed={5}
                                 // pageCount={pageCount}
                                 previousLabel="<"
                                 renderOnZeroPageCount={null}
                                 className="at-pagenation"
                             />
                         </div>
                     </div>
                     <ZoomMeetingModal isOpen={isOpenZoomModal} closeModal={closeZoomModal} nikah={nikahDetail}/>
                     <AddZoomLinkModal isOpen={isOpenThree} closeModal={closeModalThree} nikah={nikahDetail}/>
                     <UploadCerificateModal isOpen={isOpenTwo} closeModal={closeModalTwo} nikahObject={nikahDetail}/>
                     <ValidateCertificateModal isOpen={openValidateModal} closeModal={closeCertificateModal} nikah={nikahDetail} showbtn='true' setNikahList={setNikahList} />
                 </>
                )
            }

        </AuthenticatedLayout>
    );
}
