import React, {useEffect, useState} from 'react';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import {Head, Link, useForm, usePage} from '@inertiajs/inertia-react';
import AuthLayout from '@/Layouts/AuthLayout'
import {toast} from "react-toastify";
// import { router } from '@inertiajs/rea ct'


export default function Register() {
    const { pageProps} = usePage();
    const {flash} = usePage().props;

    const [isVisible, setIsVisible] = useState(false);
    const [loading, setLoading] = useState(false)
    const { data, setData, post, processing, errors, reset } = useForm({
        first_name: '',
        last_name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    // console.log({errors});
    // debugger;


    useEffect(() => {
        return () => {
            reset('password', 'password_confirmation');
        };
    }, []);


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

    const onHandleChange = (event) => {
        setData(event.target.name, event.target.type === 'checkbox' ? event.target.checked : event.target.value);
    };

    const submit = (e) => {
        e.preventDefault();
        // post(route('register'));
        post(route("register"),{
            preserveScroll: true,
            onError:function (error) {
                // setLoading(false);
            },
            onSuccess:function (response) {
                console.log({pageProps})

                console.log(response);
            }
        });
    };

    return (
        <AuthLayout>
            <Head title="Register" />
            <div className="at-loginpage w-full h-full overflow-auto">
                <div className="at-authformholder flex items-center justify-center flex-col bg-white relative w-full max-w-[600px] xl:min-h-[969px] p-[30px] mx-auto">
                    <div className="at-authhead w-full mb-[30px] lg:mb-[46px] xl:mb-[66px]">
                        <strong className="at-logo w-[100px] mx-auto mb-20 block">
                            <img
                                className="w-full block h-auto"
                                src="/assets/images/logo.png"
                            />
                        </strong>
                        {/*{console.log(errors, 'error')}*/}
                        <div className="at-authtitle">
                            <h1 className="mb-6">Signup to your Account</h1>
                            <span className="text-base font-product-sansregular text-gray1 tracking-wide">
                              Please enter the information below to Signup
                            </span>
                        </div>
                    </div>
                    <form className="w-full" onSubmit={submit} autoComplete="off">
                        <div className="at-forminputs">
                            <TextInput
                                type="text"
                                name="first_name"
                                value={data.first_name}
                                className="mt-1 block w-full "
                                // autoComplete="name"
                                isFocused={true}
                                handleChange={onHandleChange}
                                required
                                placeholder="First Name"
                            />

                            <InputError message={errors.first_name} className="mt-2" />
                        </div>
                        <div className="at-forminputs mt-4">
                            <TextInput
                                type="text"
                                name="last_name"
                                value={data.last_name}
                                className="mt-1 block w-full "
                                // autoComplete="name"
                                isFocused={true}
                                handleChange={onHandleChange}
                                required
                                placeholder="Last Name"
                            />

                            <InputError message={errors.last_name} className="mt-2" />
                        </div>
                        <div className="mt-4">
                            <TextInput
                                type="email"
                                name="email"
                                value={data.email}
                                className={`mt-1 block w-full ${
                                    errors.email ? 'has-error' : ''
                                }`}
                                autoComplete="username"
                                handleChange={onHandleChange}
                                required
                                placeholder="Email"
                            />

                            <InputError message={errors.email} className="mt-2" />
                        </div>

                        <div className="mt-6 relative">
                            <TextInput
                                type={isVisible ? 'text' : 'password'}
                                name="password"
                                value={data.password}
                                className="mt-1 block w-full"
                                autoComplete="new-password"
                                handleChange={onHandleChange}
                                required
                                placeholder="Password"
                            />
                            <button className="at-btnshowhidepass" type="button">
                                {/* Show Password Icon */}
                                {isVisible ? (
                                    <svg
                                        width="20"
                                        height="17"
                                        viewBox="0 0 20 17"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        onClick={() => setIsVisible(false)}
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            clip-rule="evenodd"
                                            d="M18.4177 0.216186C18.1248 -0.0720618 17.65 -0.0720618 17.3571 0.216186L15.2693 2.27086C13.6565 1.1629 11.8555 0.575426 10 0.575426C7.94315 0.575426 5.94745 1.29542 4.21856 2.64201C2.51054 3.96402 1.08697 5.87061 0.0614407 8.20811C-0.020262 8.39433 -0.0204886 8.60544 0.0608142 8.79184C0.946427 10.8222 2.13598 12.5255 3.55444 13.7999L1.58307 15.74L1.51045 15.8228C1.2926 16.1117 1.3168 16.5218 1.58307 16.7838C1.87596 17.0721 2.35084 17.0721 2.64373 16.7838L18.4177 1.26002L18.4903 1.17724C18.7082 0.888283 18.684 0.478229 18.4177 0.216186ZM4.61636 12.7548L6.75422 10.6508C6.32259 10.0256 6.086 9.28501 6.086 8.50118C6.086 6.36842 7.83371 4.6473 10 4.6473C10.7921 4.6473 11.5527 4.88178 12.1867 5.30457L14.1892 3.33382C12.8815 2.49055 11.4565 2.05163 10 2.05163C8.29025 2.05163 6.61992 2.65424 5.14764 3.80096C3.73781 4.89217 2.52945 6.46072 1.61706 8.40041L1.5704 8.50138L1.61575 8.60027C2.40872 10.2948 3.43049 11.7038 4.61636 12.7548ZM11.0935 6.3804C10.7618 6.21412 10.3876 6.1235 10 6.1235C8.66236 6.1235 7.586 7.18349 7.586 8.50118C7.586 8.88505 7.67671 9.2495 7.8468 9.57561L11.0935 6.3804Z"
                                            fill="#BE2D87"
                                        />
                                        <path
                                            d="M13.1366 8.31584L13.238 8.32693C13.6456 8.39909 13.9167 8.78282 13.8434 9.18403C13.556 10.7567 12.2992 11.996 10.7022 12.2813C10.2946 12.3541 9.90425 12.088 9.83026 11.6869C9.75628 11.2858 10.0267 10.9016 10.4342 10.8288C11.4152 10.6536 12.1904 9.88917 12.367 8.92273C12.4343 8.55496 12.7675 8.30017 13.1366 8.31584Z"
                                            fill="#BE2D87"
                                        />
                                        <path
                                            d="M16.997 4.81439C17.3279 4.56924 17.7981 4.63452 18.0472 4.96021C18.773 5.90914 19.4073 6.99852 19.9384 8.20722C20.0205 8.39401 20.0205 8.60586 19.9386 8.79271C17.8613 13.5289 14.1345 16.4245 10 16.4245C9.05878 16.4245 8.12751 16.2747 7.23057 15.9801C6.83766 15.851 6.62544 15.4329 6.75657 15.0463C6.8877 14.6596 7.31252 14.4507 7.70543 14.5798C8.44973 14.8242 9.22068 14.9483 10 14.9483C13.3046 14.9483 16.381 12.6864 18.2727 8.82978L18.4284 8.50138L18.3755 8.38456C17.9948 7.5784 17.5645 6.83896 17.0898 6.17414L16.8488 5.84799C16.5997 5.52231 16.666 5.05955 16.997 4.81439Z"
                                            fill="#BE2D87"
                                        />
                                    </svg>
                                ) : (
                                    <svg
                                        width="20"
                                        height="16"
                                        viewBox="0 0 20 16"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        onClick={() => setIsVisible(true)}
                                    >
                                        <path
                                            fillRule="evenodd"
                                            clipRule="evenodd"
                                            d="M9.9995 4.11346C7.8391 4.11346 6.0885 5.85313 6.0885 8.0002C6.0885 10.1465 7.83929 11.8859 9.9995 11.8859C12.1598 11.8859 13.9115 10.1463 13.9115 8.0002C13.9115 5.85325 12.16 4.11346 9.9995 4.11346ZM9.9995 5.60378C11.3317 5.60378 12.4115 6.6764 12.4115 8.0002C12.4115 9.32312 11.3316 10.3956 9.9995 10.3956C8.66771 10.3956 7.5885 9.32338 7.5885 8.0002C7.5885 6.67614 8.6676 5.60378 9.9995 5.60378Z"
                                            fill="#C0BCBC"
                                        />
                                        <path
                                            fillRule="evenodd"
                                            clipRule="evenodd"
                                            d="M10.2882 0.00481888L10.002 0C5.86108 0 2.12926 2.92308 0.0609149 7.70583C-0.020305 7.89364 -0.020305 8.10636 0.0609149 8.29416L0.204239 8.61679C2.24638 13.0931 5.77544 15.8644 9.71179 15.9952L9.998 16C14.1389 16 17.8707 13.0769 19.9391 8.29416C20.0213 8.10399 20.0202 7.88839 19.9361 7.69904L19.7968 7.38563C17.7497 2.90091 14.2192 0.135466 10.2882 0.00481888ZM10.009 1.48942L10.2479 1.49456L10.5149 1.50845C13.7122 1.73484 16.6525 4.10553 18.429 7.99911L18.4197 8.02313C16.5987 12.0005 13.5569 14.3853 10.2589 14.505L10.004 14.5088L9.74693 14.5054L9.48061 14.4915C6.38271 14.2721 3.52637 12.0344 1.73914 8.3597L1.57 7.99911L1.72658 7.66619C3.61117 3.77315 6.69148 1.49027 10.009 1.48942Z"
                                            fill="#C0BCBC"
                                        />
                                    </svg>


                                )}

                                {/* Hide Password Icon */}
                            </button>

                            <InputError message={errors.password} className="mt-2" />
                        </div>

                        <div className="mt-4 relative">
                            <TextInput
                                type={isVisible ? 'text' : 'password'}
                                name="password_confirmation"
                                value={data.password_confirmation}
                                className="mt-1 block w-full"
                                handleChange={onHandleChange}
                                required
                                placeholder="Confirm Password"
                            />
                            <button className="at-btnshowhidepass" type="button">
                                {/* Show Password Icon */}
                                {isVisible ? (
                                    <svg
                                        width="20"
                                        height="17"
                                        viewBox="0 0 20 17"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        onClick={() => setIsVisible(false)}
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            clip-rule="evenodd"
                                            d="M18.4177 0.216186C18.1248 -0.0720618 17.65 -0.0720618 17.3571 0.216186L15.2693 2.27086C13.6565 1.1629 11.8555 0.575426 10 0.575426C7.94315 0.575426 5.94745 1.29542 4.21856 2.64201C2.51054 3.96402 1.08697 5.87061 0.0614407 8.20811C-0.020262 8.39433 -0.0204886 8.60544 0.0608142 8.79184C0.946427 10.8222 2.13598 12.5255 3.55444 13.7999L1.58307 15.74L1.51045 15.8228C1.2926 16.1117 1.3168 16.5218 1.58307 16.7838C1.87596 17.0721 2.35084 17.0721 2.64373 16.7838L18.4177 1.26002L18.4903 1.17724C18.7082 0.888283 18.684 0.478229 18.4177 0.216186ZM4.61636 12.7548L6.75422 10.6508C6.32259 10.0256 6.086 9.28501 6.086 8.50118C6.086 6.36842 7.83371 4.6473 10 4.6473C10.7921 4.6473 11.5527 4.88178 12.1867 5.30457L14.1892 3.33382C12.8815 2.49055 11.4565 2.05163 10 2.05163C8.29025 2.05163 6.61992 2.65424 5.14764 3.80096C3.73781 4.89217 2.52945 6.46072 1.61706 8.40041L1.5704 8.50138L1.61575 8.60027C2.40872 10.2948 3.43049 11.7038 4.61636 12.7548ZM11.0935 6.3804C10.7618 6.21412 10.3876 6.1235 10 6.1235C8.66236 6.1235 7.586 7.18349 7.586 8.50118C7.586 8.88505 7.67671 9.2495 7.8468 9.57561L11.0935 6.3804Z"
                                            fill="#BE2D87"
                                        />
                                        <path
                                            d="M13.1366 8.31584L13.238 8.32693C13.6456 8.39909 13.9167 8.78282 13.8434 9.18403C13.556 10.7567 12.2992 11.996 10.7022 12.2813C10.2946 12.3541 9.90425 12.088 9.83026 11.6869C9.75628 11.2858 10.0267 10.9016 10.4342 10.8288C11.4152 10.6536 12.1904 9.88917 12.367 8.92273C12.4343 8.55496 12.7675 8.30017 13.1366 8.31584Z"
                                            fill="#BE2D87"
                                        />
                                        <path
                                            d="M16.997 4.81439C17.3279 4.56924 17.7981 4.63452 18.0472 4.96021C18.773 5.90914 19.4073 6.99852 19.9384 8.20722C20.0205 8.39401 20.0205 8.60586 19.9386 8.79271C17.8613 13.5289 14.1345 16.4245 10 16.4245C9.05878 16.4245 8.12751 16.2747 7.23057 15.9801C6.83766 15.851 6.62544 15.4329 6.75657 15.0463C6.8877 14.6596 7.31252 14.4507 7.70543 14.5798C8.44973 14.8242 9.22068 14.9483 10 14.9483C13.3046 14.9483 16.381 12.6864 18.2727 8.82978L18.4284 8.50138L18.3755 8.38456C17.9948 7.5784 17.5645 6.83896 17.0898 6.17414L16.8488 5.84799C16.5997 5.52231 16.666 5.05955 16.997 4.81439Z"
                                            fill="#BE2D87"
                                        />
                                    </svg>
                                ) : (
                                    <svg
                                        width="20"
                                        height="16"
                                        viewBox="0 0 20 16"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        onClick={() => setIsVisible(true)}
                                    >
                                        <path
                                            fillRule="evenodd"
                                            clipRule="evenodd"
                                            d="M9.9995 4.11346C7.8391 4.11346 6.0885 5.85313 6.0885 8.0002C6.0885 10.1465 7.83929 11.8859 9.9995 11.8859C12.1598 11.8859 13.9115 10.1463 13.9115 8.0002C13.9115 5.85325 12.16 4.11346 9.9995 4.11346ZM9.9995 5.60378C11.3317 5.60378 12.4115 6.6764 12.4115 8.0002C12.4115 9.32312 11.3316 10.3956 9.9995 10.3956C8.66771 10.3956 7.5885 9.32338 7.5885 8.0002C7.5885 6.67614 8.6676 5.60378 9.9995 5.60378Z"
                                            fill="#C0BCBC"
                                        />
                                        <path
                                            fillRule="evenodd"
                                            clipRule="evenodd"
                                            d="M10.2882 0.00481888L10.002 0C5.86108 0 2.12926 2.92308 0.0609149 7.70583C-0.020305 7.89364 -0.020305 8.10636 0.0609149 8.29416L0.204239 8.61679C2.24638 13.0931 5.77544 15.8644 9.71179 15.9952L9.998 16C14.1389 16 17.8707 13.0769 19.9391 8.29416C20.0213 8.10399 20.0202 7.88839 19.9361 7.69904L19.7968 7.38563C17.7497 2.90091 14.2192 0.135466 10.2882 0.00481888ZM10.009 1.48942L10.2479 1.49456L10.5149 1.50845C13.7122 1.73484 16.6525 4.10553 18.429 7.99911L18.4197 8.02313C16.5987 12.0005 13.5569 14.3853 10.2589 14.505L10.004 14.5088L9.74693 14.5054L9.48061 14.4915C6.38271 14.2721 3.52637 12.0344 1.73914 8.3597L1.57 7.99911L1.72658 7.66619C3.61117 3.77315 6.69148 1.49027 10.009 1.48942Z"
                                            fill="#C0BCBC"
                                        />
                                    </svg>


                                )}

                                {/* Hide Password Icon */}
                            </button>

                            <InputError message={errors.password} className="mt-2" />
                        </div>
                        <div className="flex  items-center mt-7">
                            <Link href={route('login')} className="text-sm text-themecolor font-product-sansregular" as="button">
                                Already registered?
                            </Link>
                        </div>
                        <div className="flex items-center justify-end mt-10">

                            {/*<PrimaryButton type="button" className="w-full gap-2" processing={processing}>*/}
                            {/*    Register*/}
                            {/*    {loading && <div className="lds-dual-ring"></div>}*/}
                            {/*</PrimaryButton>*/}
                            <button type="submit" className="w-full gap-2 inline-flex items-center justify-center  bglinear-gradient py-3
                            px-6 xl:py-4 xl:px-12 border border-transparent rounded-[10px] font-product_sansregular font-bold text-lg
                             bg-gray1 text-white capitalize tracking-widest active:bg-black transition ease-in-out duration-150">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </AuthLayout>
    );
}
