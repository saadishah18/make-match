import React, {useEffect, useState} from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import TextInput from "@/Components/TextInput";
// import InputError from "@/Components/InputError";
import {Head, Link, useForm, usePage} from '@inertiajs/inertia-react';
import PrimaryButton from "@/Components/PrimaryButton";
import {toast} from "react-toastify";
import {Inertia} from "@inertiajs/inertia";
import AddWitnessModal from "@/Components/AddWitnessModal";
import ChangePasswordModal from "@/Components/ChangePasswordModal";

export default function Profile(props) {
    const {flash} = usePage().props;
    const user = props.user.data;
    // console.log(user,props);
    // console.log(user);
    // debugger;
    const { data, setData, post, processing, errors, reset } = useForm({
        first_name: user.first_name, last_name:user.last_name, email:user.email ,
        phone:user.phone, gender:user.gender, id_card_number:user.id_card_number
    });
    let [isOpen, setIsOpen] = useState(false)
    function closeModal() {
        setIsOpen(false)
    }

    const openChangePassword = () => {
        setIsOpen(true);
    }
    const submitHandler = (e) => {
        e.preventDefault();
        post(route('updateProfile'),{
            preserveScroll: true,
            onError:function (error) {
                toast.error(error)
            },
            onSuccess:function (response) {
                toast.success(response.message);
            }
        });
    };

    const onHandleChange = (event) => {
        setData(event.target.name, event.target.type === 'checkbox' ? event.target.checked : event.target.value);
    };

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
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>}
        >
            <Head title="Profile" />
            <div className="at-pagehead mb-6 block md:flex items-center justify-between">
                <h3 className="text-black text-[1.75rem] leading-7 font-product_sans_mediumregular">Profile</h3>
            </div>

            <form className="at-formaddemployee w-full" onSubmit={submitHandler}>
                <fieldset className="flex w-full gap-x-8">
                    <div className="grid grid-cols-12 col-span-12 gap-8">
                        <div className="col-span-12">
                            <label className="block text-sm text-black font-product_sansregular mb-2">First Name</label>
                            <TextInput
                                className="!border !border-[#C0BCBC] !h-[50px] block "
                                type="text"
                                placeholder="First Name"
                                name="first_name"
                                value={data.first_name}
                                handleChange={onHandleChange}
                            />
                            {errors.first_name && <div>{errors.first_name}</div>}
                        </div>
                        <div className="col-span-12">
                            <label className="block text-sm text-black font-product_sansregular mb-2">Last Name</label>
                            <TextInput
                                className="!border !border-[#C0BCBC] !h-[50px] block "
                                type="text"
                                placeholder="Last Name"
                                name="last_name"
                                value={data.last_name}
                                handleChange={onHandleChange}
                            />
                            {errors.last_name && <div>{errors.last_name}</div>}
                        </div>
                        <div className="col-span-12">
                            <label className="block text-sm text-black font-product_sansregular mb-2">Email</label>
                            <TextInput
                                className="!border !border-[#C0BCBC] !h-[50px] block "
                                type="email"
                                placeholder="Email"
                                name="email"
                                value={data.email}
                                handleChange={onHandleChange}
                            />
                            {errors.email && <div>{errors.email}</div>}
                        </div>

                    </div>
                    <div className="grid grid-cols-12 col-span-12 gap-8">
                        <div className="col-span-12">
                            <label className="block text-sm text-black font-product_sansregular mb-2">ID card #</label>
                            <TextInput
                                className="!border !border-[#C0BCBC] !h-[50px] block "
                                type="number"
                                placeholder="ID #"
                                name="id_card_number"
                                value={data.id_card_number}
                                handleChange={onHandleChange}
                            />
                            { errors.id_card_number && <div>{errors.id_card_number}</div> }
                        </div>
                        <div className="col-span-12">
                            <label className="block text-sm text-black font-product_sansregular mb-2">
                                Phone
                            </label>
                            <TextInput
                                className="!border !border-[#C0BCBC] !h-[50px] block "
                                type="text"
                                placeholder=""
                                name="phone"
                                value={data.phone}
                                handleChange={onHandleChange}
                            />
                            {errors.phone && <div>{errors.phone}</div>}
                        </div>
                        {/*<div className="col-span-12">*/}
                        {/*    <label className="block text-sm text-black font-product_sansregular mb-2">*/}
                        {/*        Gender*/}
                        {/*    </label>*/}
                        {/*    <input type="radio" value="male" name="gender"*/}
                        {/*           checked={data.gender === 'male'}*/}
                        {/*           onChange={onHandleChange}/> &nbsp; Male &nbsp;*/}
                        {/*    <input type="radio" value="female" name="gender"*/}
                        {/*           checked={data.gender === 'female'}*/}
                        {/*           onChange={onHandleChange}/>&nbsp; Female*/}
                        {/*    {errors.gender && <div>{errors.gender}</div>}*/}
                        {/*</div>*/}

                        <div className="col-span-12">
                            <PrimaryButton type="button" onclick={openChangePassword} className="mt-4">Change Password</PrimaryButton>
                        </div>
                    </div>

                </fieldset>

                <PrimaryButton type="submit" className="mt-4">Update Profile</PrimaryButton>
            </form>
            <ChangePasswordModal isOpen={isOpen} closeModal={closeModal}/>

        </AuthenticatedLayout>
    );
}
