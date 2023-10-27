import React, {useEffect, useState} from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, Link, usePage} from '@inertiajs/inertia-react';
import PrimaryButton from "@/Components/PrimaryButton";
import DeleteModal from "@/Components/DeleteModal";
import {ImamsData} from "../../../../Data/ImamsData";
import AddImamModal from "@/Components/AddImamModal";
import {Switch} from '@headlessui/react'
import ReactPaginate from "react-paginate";
import axios from "axios";
import ToggleButton from "@/Components/ToggleButton";
import {Inertia} from "@inertiajs/inertia";
import {toast} from "react-toastify";


export default function Imams(props) {
    const {flash} = usePage().props;
    let [isOpen, setIsOpen] = useState(false)
    let [isOpenOne, setIsOpenOne] = useState(false);
    const [loader, setLoader] = useState(true);
    const [imams, setImams] = useState([]);
    let [deleteItem, setDeleteItem] = useState('');
    const [searchInput, setSearchInput] = useState("");


    useEffect( () => {
        setImams(props.imams)
        setLoader(false);
    },[props]);


    function closeModal() {
        setIsOpen(false)
    }

    function openModal() {
        setIsOpen(true)
    }

    function closeModalOne() {
        setIsOpenOne(false)
    }

    function openModalOne() {
        setIsOpenOne(true)
        console.log("abc")
    }

    function opendeleteModal(id) {
        setDeleteItem(id)
        setIsOpen(true)
    }

    function deleteItemHandler() {
        Inertia.post(route("delete-imam", {
            imam_id: deleteItem
        }), {
            onError: function (errors) {
                console.log({errors});
                toast.error(errors.error);
            },
            onSuccess: function (response) {
                console.log({response});
                // alert('success');
                toast.success('Imam deleted Successfully');
            }
        });
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

    const handleSearchInputChange = (event) => {
        setSearchInput(event.target.value);
    };
    const filterData = imams.filter((row) => {
        const { first_name, last_name, email, imam_nikahs_count } = row;
        // let searchValue = event.target.value;
        const searchValue = searchInput.toLowerCase();

        return (first_name.toLowerCase().includes(searchValue) ||
            last_name.toLowerCase().includes(searchValue) ||
            email.toLowerCase().includes(searchValue)
            // imam_nikahs_count.toLowerCase.includes(searchValue)
        );
    });

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Imams</h2>}
        >
            <Head title="Imam Management"/>
            {
                loader ? 'Loading' : (
                    <>
                        <div className="at-pagehead mb-6 block md:flex items-center justify-between">
                            <h3 className="text-black text-[1.75rem] leading-7 font-product_sans_mediumregular">Imams</h3>
                            <form className="at-searchform min-w-[370px]" onSubmit={event => event.preventDefault()}>
                                <fieldset className="">
                                    <div className="form-group relative">
                                        <input
                                            className="h-[50px] rounded-lg border pr-[40px] border-bordercolor text-black placeholder:text-gray1 focus:border-black focus:ring-0"
                                            type="text"
                                            name="search"
                                            placeholder="Search" onChange={handleSearchInputChange}
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
                            {/*<PrimaryButton*/}
                            {/*    type="button"*/}
                            {/*    onclick={openModalOne}*/}
                            {/*>*/}
                            {/*    Add Imam*/}
                            {/*</PrimaryButton>*/}
                        </div>
                        <div className="at-ideasarea w-full">
                            <div className="at-themetablearea at-ideastablearea w-full">
                                <table className="at-themetable">
                                    <thead>
                                    <tr>
                                        <th className="font-product_sansbold">Name</th>
                                        <th className="font-product_sansbold">Email</th>
                                        <th className="font-product_sansbold">Nikah Count</th>
                                        <th className="font-product_sansbold">Status</th>
                                        <th className="!text-center font-product_sansbold">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    {filterData.length ? filterData.map((item, i) => (
                                        <tr key={i}>
                                            <td data-title="name">
                                                <div className="at-themeemployeinfo at-bdleftborder">
                                                    <div className="at-usernameemail">
                                                        <h3 className="text-black font-product_sansregular text-base leading-4 mb-2 tracking-wide">{item.first_name} {item.last_name}</h3>
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-title="email" className="text-left">
                                                <span>{item.email}</span>
                                            </td>
                                            <td data-title="Department" className="text-left">
                                                <span>{item.imam_nikahs_count}</span>
                                            </td>
                                            <td data-title="status" className="text-left">
                                                <ToggleButton status={item.is_active} imam_id={item.id} />
                                            </td>
                                            <td data-title="Action">
                                                <button
                                                    type="button"
                                                    className="w-[25px] h-[25px] flex justify-center items-center rounded-[10px] bg-deletecolor bg-opacity-10 mx-auto"
                                                    onClick={() => opendeleteModal(item.id)}
                                                >
                                                    <span className="at-themetoolip">Delete</span>
                                                    <img src='/assets/images/svg/delete.svg' alt="Delete Icon"/>
                                                </button>
                                            </td>
                                        </tr>
                                )) :  <tr>
                                        <td colSpan={5}>
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
                                    pageRangeDisplayed={2}
                                    // pageCount={pageCount}
                                    previousLabel="<"
                                    renderOnZeroPageCount={null}
                                    className="at-pagenation"
                                />
                            </div>
                        </div>
                        <DeleteModal isOpen={isOpen} closeModal={closeModal} deletehandler={deleteItemHandler}/>
                        {/*<AddImamModal isOpen={isOpenOne} closeModal={closeModalOne}/>*/}
                    </>
                )
            }

        </AuthenticatedLayout>
    );
}
