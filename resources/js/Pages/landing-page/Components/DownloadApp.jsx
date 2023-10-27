import { Link } from "@inertiajs/inertia-react";

const DownloadApp = () => {
    return (
        <div className="relative pt-[66px] pb-[66px] lg:pb-[36px] bg-[url(/assets/images/landingpage-img/download-app-bg.jpg)] sm:bg-no-repeat">
            <div className="container">
                <div className="grid sm:grid-cols-2 items-center gap-[50px] lg:gap-[100px] xl:gap-[278px]">
                    <div className="relative">
                        <h2 className="text-white font-gilroy-bold text-4xl xl:text-5xl mb-6 leading-[3.688rem] xl:leading-[3.688rem]">
                            Download the App today and get Nikahfied
                        </h2>
                        <p className="font-gilroy-medium text-xl text-white text-opacity-70 mb-14">
                            It has never been easier to complete your Nikah
                            within 48 hours. This App offers a safe, reliable,
                            and halal solution. Experience the convenience,
                            efficiency, and peace of mind that MyNikahNow
                            provides. Download now and embark on your Nikah
                            journey with confidence.
                        </p>
                        {/*<div className="flex 2xs:flex-col xs:flex-row items-center gap-4">
                            <Link
                                href="/home"
                                className="flex items-center border border-white border-opacity-30 rounded-2xl py-3 2xs:w-full min-w-[177px] justify-center"
                            >
                                <img
                                    src="/assets/images/apple-icon.svg"
                                    alt="app image"
                                    className="mr-3 w-8 h-8"
                                />
                                <div className="flex flex-col">
                                    <span className="flex text-[10px] text-white text-opacity-70 font-gilroy-regular">
                                        Download on the
                                    </span>
                                    <strong className="text-base text-white font-gilroy-semibold">
                                        App Store
                                    </strong>
                                </div>
                            </Link>
                            <Link
                                href="/home"
                                className="flex items-center border border-white border-opacity-30 rounded-2xl py-3 2xs:w-full min-w-[177px] justify-center"
                            >
                                <img
                                    src="/assets/images/playstore-Icon.svg"
                                    alt="app image"
                                    className="mr-3 w-8 h-8"
                                />
                                <div className="">
                                    <span className="flex text-[10px] text-white text-opacity-70 font-gilroy-regular">
                                        Download on the
                                    </span>
                                    <strong className="text-base text-white font-gilroy-semibold">
                                        Google Play
                                    </strong>
                                </div>
                            </Link>
                        </div>*/}
                    </div>
                    <div className="relative">
                        <img
                            src="/assets/images/landingpage-img/download-app-img.jpg"
                            alt="download image"
                            className="relative z-[1]"
                        />
                    </div>
                </div>
            </div>
        </div>
    );
};

export default DownloadApp;
