const Appfeatures = () => {
    return (
        <div
            id="features"
            className="relative pt-[50px] lg:pt-[100px] pb-[100px] md:pb-[150px] lg:pb-[184px] after:absolute after:left-[-200px] xl:after:left-0 after:bottom-[80px] after:bg-[url(/assets/images/landingpage-img/feature-patron.svg)] after:bg-no-repeat after:w-[400px] after:h-[350px]"
        >
            <div className="container">
                <h2 className="text-center text-[2.7rem] md:text-[3.3rem] xl:text-[4rem] 2xl:text-[4.3rem] leading-[4rem] md:leading-[5rem] lg:leading-[5.5rem] xl:leading-[5.813rem] font-gilroy-bold mb-5 xl:mb-8">
                    MyNikahNow App Features{" "}
                </h2>
                <p className="text-center max-w-[1242px] mx-auto font-gilroy-medium text-xl mb-12 xl:mb-[72px] text-[#909191]">
                    Get ready to experience a world of convenience with our
                    feature-packed app that covers all your Nikah needs. Whether
                    you're looking for a basic online nikah ceremony service or
                    need assistance with talaq, ruju, or khulu, MyNikahNow App
                    has you covered.
                </p>
                <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-16">
                    <div className="text-center pt-16 px-6 ">
                        <img
                            src="/assets/images/landingpage-img/nikah-icon.svg"
                            alt="icon"
                            className="mx-auto mb-8 sm:mb-14"
                        />
                        <h3 className="text-[2rem] text-black font-gilroy-bold mb-3">
                            Nikah
                        </h3>
                        <p className="text-[#909191] text-base pb-16 relative relative after:absolute after:left-1/2 after:bottom-0 after:translate-x-[-50%] after:h-[2px] after:w-[220px] after:bg-themecolor">
                            Get your Nikah done in a breeze with MyNikahNow App!
                            Our fully guided process takes you from application
                            to ceremony, with fast-track service in as little as
                            48 hours.
                        </p>
                    </div>
                    <div className="text-center xl:mt-20 pt-16 px-6">
                        <img
                            src="/assets/images/landingpage-img/talaq-icon.svg"
                            alt="icon"
                            className="mx-auto mb-8 sm:mb-14"
                        />
                        <h3 className="text-[2rem] text-black font-gilroy-bold mb-3">
                            Talaq
                        </h3>
                        <p className="text-[#909191] text-base pb-16 relative relative after:absolute after:left-1/2 after:bottom-0 after:translate-x-[-50%] after:h-[2px] after:w-[220px] after:bg-themecolor">
                            If ending the Nikah seems like the only option,
                            MyNikahNow App has you covered. Our App provides a
                            straightforward and guided process for handling
                            talaq, making a difficult decision a little easier.
                        </p>
                    </div>
                    <div className="text-center pt-16 px-6">
                        <img
                            src="/assets/images/landingpage-img/ruju-icon.svg"
                            alt="icon"
                            className="mx-auto mb-8 sm:mb-14"
                        />
                        <h3 className="text-[2rem] text-black font-gilroy-bold mb-3">
                            Ruju
                        </h3>
                        <p className="text-[#909191] text-base pb-16 relative after:absolute after:left-1/2 after:bottom-0 after:translate-x-[-50%] after:h-[2px] after:w-[220px] after:bg-themecolor">
                            Undoing a talaq is made easy with the App's ruju
                            feature. With just one click, you can initiate the
                            process of undoing a talaq within the 3-month 'idda
                            period.
                        </p>
                    </div>
                    <div className="text-center xl:mt-20 pt-16 px-6">
                        <img
                            src="/assets/images/landingpage-img/khulu-icon.svg"
                            alt="icon"
                            className="mx-auto mb-8 sm:mb-14"
                        />
                        <h3 className="text-[2rem] text-black font-gilroy-bold mb-3">
                            Khulu
                        </h3>
                        <p className="text-[#909191] text-base pb-16 relative after:absolute after:left-1/2 after:bottom-0 after:translate-x-[-50%] after:h-[2px] after:w-[220px] after:bg-themecolor">
                            If your partner isn't abiding by their
                            responsibilities, this App provides a crucial and
                            Imam-guided tool for women to initiate the khulu
                            process.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Appfeatures;
