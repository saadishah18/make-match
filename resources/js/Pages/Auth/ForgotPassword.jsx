import React, {useState} from 'react';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import {Head, Link, useForm, usePage} from '@inertiajs/inertia-react';
import AuthLayout from '@/Layouts/AuthLayout';
import EmailSentModal from '@/Components/EmailSentModal';

export default function ForgotPassword({status, errors}) {
    let [isOpen, setIsOpen] = useState(false)
    const [loading, setLoading] = useState(false);
    const [isVisible,setIsVisible]=useState(false);


    function closeModal() {
        setIsOpen(false)
    }

    function openModal() {
        setIsOpen(true)
    }

    const {data, setData, post, processing} = useForm({
        email: '',
    });

    const onHandleChange = (event) => {
        setData(event.target.name, event.target.value);
    };

    const submit = (e) => {
        e.preventDefault();
        setLoading(true)
        // openModal()
        post(route('password.email'),{
            preserveScroll: true,
            onError:function () {
                setLoading(false);
            },
            onSuccess:function () {
                setLoading(false);
                openModal()
            }
        });
    };
    // setLoading(false);

    return (
        <AuthLayout>
            <Head title="Forgot Password"/>
            <div className="at-loginpage w-full h-full overflow-auto">
                <div className="at-authformholder flex items-center justify-center flex-col bg-white relative w-full max-w-[600px] min-h-[969px] p-[30px] mx-auto">
                    <div className="at-authhead w-full mb-[30px] lg:mb-[46px] xl:mb-[66px]">
                        <strong className="at-logo w-[100px] mx-auto mb-20 block">
                            <img
                                className="w-full block h-auto"
                                src="/assets/images/logo.png"
                            />
                        </strong>
                        <div className="at-authtitle">
                            <h1 className="mb-6">Forgot Password?</h1>
                            <span className="text-base font-product-sansregular text-gray1 tracking-wide">Please enter your registered email below to reset your account password</span>
                        </div>
                    </div>
                    <form className="w-full" onSubmit={submit}>
                        <div className="at-forminputs">
                            <TextInput
                                type="text"
                                name="email"
                                value={data.email}
                                className="mt-1 block w-full"
                                isFocused={true}
                                handleChange={onHandleChange}
                            />

                            <InputError message={errors.email} className="mt-2"/>
                        </div>

                        <div className="flex items-center justify-center w-full mt-14">
                            {/*<Link className="w-full" href="/reset-password">*/}
                            {/*   <button type="submit" */}
                            {/*       className="inline-flex items-center justify-center gap-3  bg-themecolor py-3 px-6 xl:py-4 xl:px-12 border border-transparent rounded-[10px] font-bold font-product_sansregular text-lg text-white capitalize tracking-widest active:bg-black hover:bg-black transition ease-in-out duration-150 false w-full">*/}
                            {/*       Submit*/}
                            {/*   </button>*/}
                            {/*</Link> */}

                            <PrimaryButton
                                type="submit"
                                className="w-full gap-2"
                                processing={!data.email ? true : processing}
                            >
                                Submit
                                {/* Spinner Start */}
                                {loading && <div className="lds-dual-ring"></div>}
                            </PrimaryButton>
                        </div>
                    </form>
                    <div className="flex items-center justify-center mt-12">
                        <span className="text-base text-black font-product-sansregular">Return to <Link className="text-themecolor font-productsans-bold" href="/login" as="button"> Login</Link></span>
                    </div>
                </div>
            </div>
            <EmailSentModal isOpen={isOpen} closeModal={closeModal} email={data.email}/>
        </AuthLayout>
    );
}
