import React, {Fragment, useEffect, useState} from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, Link, usePage} from '@inertiajs/inertia-react';
import {HiEllipsisVertical} from "react-icons/hi2";
import {Menu, Transition} from '@headlessui/react'
import AssignImamModal from "@/Components/AssignImamModal";
import AssignWalliModal from "@/Components/AssignWalli";
import AssignWitnessesModal from "@/Components/AssignWitnesses";
import ReactPaginate from "react-paginate";
import LoadingCircle from "@/Components/LoadingCircle";
import {toast} from "react-toastify";
import moment from "moment";
import 'moment-timezone'

export default function NikahManagement(props) {
    let [isOpen, setIsOpen] = useState(false)
    let [isOpenTwo, setIsOpenTwo] = useState(false)
    let [isOpenThree, setIsOpenThree] = useState(false)
    const [loader, setLoader] = useState(true);
    const [nikahList, setNikahList] = useState([]);
    const [defaultList, setDefaultList] = useState([]);
    const [imams, setImams] = useState([]);
    const [witnesses, setWitnesses] = useState([]);
    const [nikah_id, setNikahID] = useState('');
    const [type, setType] = useState('');
    const [searchInput, setSearchInput] = useState("");
    const {auth} = usePage().props;
    useEffect(() =>  {
        setNikahList(props?.nikahs?.data)
        setDefaultList(props?.nikahs?.data)
        setLoader(false);
        console.log(nikahList);

    }, [props]);

    function closeModal() {
        setIsOpen(false);
    }

    function openModal() {
        setIsOpen(true);
    }

    const handleAssignImam = (date, time, nikah_id) => {
        setLoader(true);
        axios.post(route('get-available-imams'), {
            type:'assign-imam',
            nikah_date: moment(date).format('YYYY-MM-DD'),
            start_time: time,
            nikah_id: nikah_id
        }).then(function (response) {
            const {data} = response.data
            // console.log(data);
            setImams(data.imams)
            setNikahID(data.nikah_id)
            setLoader(false);
            setType('nikah')
            openModal();

        }).catch(function (error) {
            setLoader(false);
            // console.log({error});
            let {response} = error;
            setLoader(false);
            toast.error(response.data.message)
        });
    }

    const handleChangeImam = (date, time, nikah_id, imam_id) => {
        setLoader(true);
        axios.post(route('get-available-imams'), {
            type:'change-imam',
            nikah_date: moment(date).format('YYYY-MM-DD'),
            start_time: time,
            nikah_id: nikah_id,
            imam_id: imam_id
        }).then(function (response) {
            const {data} = response.data
            console.log(data);
            setImams(data.imams);
            setNikahID(data.nikah_id);
            setLoader(false);
            setType('change-imam')
            openModal();

        }).catch(function (error) {
            setLoader(false);
            console.log({error});
            let {response} = error;
            setLoader(false);
            toast.error(response.data.message)
        });
    }

    function closeModalTwo() {
        setIsOpenTwo(false);
    }

    function openModalTwo() {
        setIsOpenTwo(true);
    }

    function closeModalThree() {
        setIsOpenThree(false);
    }

    function openAssignWitnessModal(date, time, nikah_id) {
        setLoader(true);

        async function fetchWitnesses() {
            /* let response =  props.witnesses
             await setWitnessList(response)*/
            await axios.post(route('get-witness-to-assign'), {
                nikah_date: date,
                start_time: time,
                nikah_id: nikah_id
            }).then(function (response) {
                const {data} = response
                console.log(data.data.length);
                if (data.data.length == 0) {
                    setLoader(false);
                    toast.info(data.message);
                } else {
                    console.log(data.data.witnesses)
                    const wintess_array = data.data.witnesses;
                    setNikahID(nikah_id)
                    setWitnesses(wintess_array);
                    setIsOpenThree(true);
                    setLoader(false);
                }


            }).catch(function (error) {
                console.log({error})
                setLoader(false);
                // console.log(error);
            });
        }

        fetchWitnesses();
    }

    const handleSearchInputChange = (event) => {
        setSearchInput(event.target.value);
    };

    const filterData = nikahList.filter((row) => {
        const { groom, bride, assigned_imam, nikah_type } = row;
        // let searchValue = event.target.value;
        const searchValue = searchInput.toLowerCase();

        return (groom.toLowerCase().includes(searchValue) ||
            bride.toLowerCase().includes(searchValue) ||
            assigned_imam.toLowerCase().includes(searchValue) ||
        nikah_type.toLowerCase().includes(searchValue)
        );
    });

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Nikah Management
                </h2>
            }
        >
            <Head title="Nikah Management" />
            {loader ? (
                <LoadingCircle loading={loader} />
            ) : (
                <>
                    <div className="at-pagehead mb-6 block md:flex items-center justify-between">
                        <h3 className="text-black text-[1.75rem] leading-7 font-product_sans_mediumregular">
                            Nikah Management
                        </h3>
                        <form className="at-searchform min-w-[370px]" >
                            <fieldset className="">
                                <div className="form-group relative">
                                    <input
                                        className="h-[50px] rounded-lg border pr-[40px] border-bordercolor text-black placeholder:text-gray1 focus:border-black focus:ring-0"
                                        type="text" name="search" placeholder="Search" onChange={handleSearchInputChange}
                                    />
                                    <svg
                                        className="absolute top-1/2 -translate-y-1/2 right-3 pointer-events-none"
                                        width="20" height="20" viewBox="0 0 21 21" fill="none"
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
                                        <th className="font-product_sansbold">Assigned Imam</th>
                                        <th className="!text-center font-product_sansbold">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    {filterData.length ?  filterData.map((item, i) => (
                                        <tr key={i}>
                                            <td data-title="groom name">
                                                <div className="at-themeemployeinfo at-bdleftborder">
                                                    <div className="at-usernameemail">
                                                        <h3 className="text-black font-product_sansregular text-base leading-4 mb-2 tracking-wide">{item.groom}</h3>
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-title="bride name">
                                                <div className="at-usernameemail">
                                                    <h3 className="text-black font-product_sansregular text-base leading-4 mb-2 tracking-wide">{item.bride}</h3>
                                                </div>
                                            </td>
                                            <td data-title="type" className="text-left">
                                                <span>{item.nikah_type}</span>
                                            </td>
                                            <td data-title="Time" className="text-left">
                                                <span>{item.start_time}</span>
                                            </td>
                                            <td data-title="Nikah Date" className="text-left">
                                                <span>{item.start_date}</span>
                                            </td>
                                            <td data-title="Status">
                                             <span
                                                 className={`at-empstatus flex min-w-[100px] max-w-[100px] rounded-[8px] h-10 items-center justify-center text-base leading-4 text-black font-product-sansregular ${
                                                     item.is_validated == 0 ? 'at-bgcolorpending' : 'at-bgrated'
                                                 }`}
                                             >
                                                    {item.is_validated == 0 ? 'Pending' : 'Completed'}
                                              </span>
                                            </td>
                                            <td data-title="Imam Name" className="text-left">
                                                <span>{item.assigned_imam}</span>
                                            </td>
                                            <td data-title="Action" className="px-2">
                                                <Menu as="div" className="relative inline-block text-left">
                                                    <Menu.Button
                                                        className="bg-themecolor text-white text-[30px] rounded-lg font-product_sansregular px-[6px] min-h-[40px] flex items-center justify-center mx-auto">
                                                        <HiEllipsisVertical/>
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
                                                        <Menu.Items
                                                            className="absolute z-10 right-0 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                                            {
                                                                item.imam_id == null ?
                                                                    <>
                                                                        <Menu.Item key={i}>
                                                                            <button
                                                                                type="button"
                                                                                className="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                                                                onClick={() => handleAssignImam(item.nikah_date, item.start_time_simple, item.nikah_id)}
                                                                            >
                                                                                Assign Imam
                                                                            </button>
                                                                        </Menu.Item>
                                                                    </> :
                                                                    <>
                                                                        <Menu.Item key={i}>
                                                                            <button
                                                                                type="button"
                                                                                className="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                                                                onClick={() => handleChangeImam(item.nikah_date, item.start_time_simple, item.nikah_id, item.imam_id)}
                                                                            >
                                                                                Change Assigned Imam
                                                                            </button>
                                                                        </Menu.Item>
                                                                    </>
                                                            }

                                                            {
                                                                item.services.map((service, key) => (
                                                                    <>
                                                                        {/* {
                                                                            service.slug == "nikah_with_wakeel" ? <>
                                                                                <Menu.Item>
                                                                                    <button type="button" className="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                                                                            onClick={openModalTwo}
                                                                                    >
                                                                                        Assign Walli
                                                                                    </button>
                                                                                </Menu.Item>
                                                                            </> : ''
                                                                        }*/}
                                                                        {
                                                                            service.slug == "nikah_witness" ? <>
                                                                                <Menu.Item  key={key}>
                                                                                    <button type="button" key={key}
                                                                                            className="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                                                                            onClick={() => openAssignWitnessModal(item.nikah_date, item.start_time, item.nikah_id)}
                                                                                    >
                                                                                        Assign Witnesses
                                                                                    </button>
                                                                                </Menu.Item>
                                                                            </> : ''
                                                                        }
                                                                    </>
                                                                ))
                                                            }
                                                            <Menu.Item key={i}>
                                                                <Link href={route('nikahdetails', [item.nikah_id])} as="button">
                                                                    <button type="button"
                                                                            className="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                                                        View Detail
                                                                    </button>
                                                                </Link>
                                                            </Menu.Item>
                                                        </Menu.Items>
                                                    </Transition>
                                                </Menu>
                                            </td>
                                        </tr>
                                    )) :  <div className="flex justify-center items-center min-h-[650px]">
                                        <img
                                            src="/assets/images/nodata-found.png"
                                            alt="no data found"
                                        />
                                    </div>}
                                    </tbody>
                                </table>
                                {/*<ReactPaginate
                                    breakLabel="..."
                                    nextLabel=">"
                                    onPageChange={handlePageClick}
                                    pageRangeDisplayed={5}
                                    pageCount={pageCount}
                                    previousLabel="<"
                                    renderOnZeroPageCount={null}
                                    className="at-pagenation"
                                />*/}
                        </div>

                    </div>
                    <AssignImamModal
                        imamslist={imams}
                        isOpen={isOpen}
                        closeModal={closeModal}
                        nikah_id={nikah_id}
                        type={type}
                    />
                    {/*<AssignWalliModal isOpen={isOpenTwo} closeModal={closeModalTwo}/>*/}
                    <AssignWitnessesModal
                        isOpen={isOpenThree}
                        closeModal={closeModalThree}
                        nikah_id={nikah_id}
                        witnessArray={witnesses}
                    />
                </>
            )}
        </AuthenticatedLayout>
    );
}
