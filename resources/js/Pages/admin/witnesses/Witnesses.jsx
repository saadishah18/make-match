import React, {useEffect, useState} from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, Link, usePage} from '@inertiajs/inertia-react';
import PrimaryButton from "@/Components/PrimaryButton";
import {WitnessesData} from "../../../../Data/WitnessesData";
import DeleteModal from "@/Components/DeleteModal";
import AddWitnessModal from "@/Components/AddWitnessModal";
import ReactPaginate from "react-paginate";
import LoadingCircle from "@/Components/LoadingCircle";
import {Inertia} from "@inertiajs/inertia";
import {toast} from 'react-toastify';
import ToasterNotification from "@/Components/ToasterNotification";

export default function Witnesses(props) {
    const {flash} = usePage().props;
    // console.log('prop',{props})
    let [isOpen, setIsOpen] = useState(false)
    let [isOpenOne, setIsOpenOne] = useState(false)
    const [witnessList, setWitnessList] = useState(props.witnesses);
    const [loader, setLoader] = useState(true);
    let [deleteItem, setDeleteItem] = useState('');

    useEffect(() => {
        async function fetchWitnesses() {
            let response = props.witnesses
            await setWitnessList(response)
        }

        fetchWitnesses();
        // console.log('check',{witnessList});
        setLoader(false);
    }, [props]);

    function closeModal() {
        setIsOpen(false)
    }

    function closeModalOne() {
        setIsOpenOne(false)
    }

    function openModalOne() {
        setIsOpenOne(true)
    }

    function opendeleteModal(id) {
        setDeleteItem(id)
        setIsOpen(true)
    }

    function deleteItemHandler() {
        Inertia.post(route("witness.delete", {
            witness_id: deleteItem
        }), {
            onError: function (errors) {
                console.log({errors});
                toast.error(errors.error);
            },
            onSuccess: function (response) {
                console.log({response});
                // alert('success');
                toast.success('Question deleted Successfully');
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

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Witnesses</h2>}
        >
            <Head title="Witnesses"/>

            {
                loader ? <LoadingCircle loading={loader}/> : (
                    <>
                        <div className="at-pagehead mb-6 block md:flex items-center justify-between">
                            <h3 className="text-black text-[1.75rem] leading-7 font-product_sans_mediumregular">Witnesses
                                Library</h3>
                            <PrimaryButton
                                type="button"
                                // onClick={openModalOne}
                                onclick={openModalOne}
                            >
                                Add Witnesses
                            </PrimaryButton>
                        </div>
                        <div className="at-ideasarea w-full">
                            <div className="at-themetablearea at-ideastablearea w-full">
                                <table className="at-themetable">
                                    <thead>
                                    <tr>
                                        <th className="font-product_sansbold">Name</th>
                                        <th className="font-product_sansbold">Email</th>
                                        {/*<th className="font-product_sansbold">Status</th>*/}
                                        <th className="!text-center font-product_sansbold">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    {witnessList.length ? witnessList.map((item, i) => (
                                        <tr key={i}>
                                            <td data-title="Employee">
                                                <div className="at-themeemployeinfo at-bdleftborder">
                                                    <div className="at-usernameemail">
                                                        <h3 className="text-black font-product_sansregular text-base leading-4 mb-2 tracking-wide">{item.first_name}</h3>
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-title="Designation" className="text-left">
                                                <span>{item.email}</span>
                                            </td>
                                            {/*  <td data-title="Department">
                                    <span
                                        className={`at-empstatus flex min-w-[100px] max-w-[100px] rounded-[8px] h-10 items-center justify-center text-base leading-4 text-black font-product-sansregular ${
                                            item.status == 'Pending'
                                                ? 'at-bgcolorpending'
                                                : 'at-bgrated'
                                        }`}
                                    >
                                           Pending
                                    </span>
                                            </td>*/}
                                            <td data-title="Action">
                                                <button
                                                    type="button"
                                                    className="w-[40px] h-[40px] flex justify-center items-center rounded-[10px] bg-deletecolor bg-opacity-10 mx-auto"
                                                    onClick={() => opendeleteModal(item.id)}
                                                >
                                                    <span className="at-themetoolip">Delete</span>
                                                    <img src='/assets/images/svg/delete.svg' alt="Delete Icon"/>
                                                </button>
                                            </td>
                                        </tr>
                                    )) : <tr>
                                        <td colSpan={3}>
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
                        <DeleteModal isOpen={isOpen} closeModal={closeModal} deletehandler={deleteItemHandler}/>
                        {/*<AddWitnessModal isOpen={isOpenOne} oldwitness={witnessList} loadwitness={setWitnessList} closeModal={closeModalOne}/>*/}
                        <AddWitnessModal isOpen={isOpenOne} closeModal={closeModalOne}/>
                    </>
                )
            }

        </AuthenticatedLayout>
    );
}
