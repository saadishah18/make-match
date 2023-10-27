const About = () => {
    return (
        <div
            id="aboutus"
            className="relative pt-[100px] lg:pt-[144px] pb-[50px] lg:pb-[100px] after:absolute after:right-0 after:bottom-[-160px] after:bg-[url(/assets/images/landingpage-img/about-patron3.svg)] after:bg-no-repeat after:w-[380px] after:h-[610px]"
        >
            <div className="container">
                <div className="grid sm:grid-cols-2 items-center gap-[40px] md:gap-[60px] xl:gap-[102px]">
                    <div className="relative">
                        <img
                            src="/assets/images/landingpage-img/about-img.png"
                            alt="banner image"
                            className="relative z-[1]"
                        />
                    </div>
                    <div className="relative after:absolute after:right-[180px] after:top-[-80px] after:bg-[url(/assets/images/landingpage-img/about-patron2.svg)] after:bg-no-repeat after:w-[135px] after:h-[135px]">
                        <span className="font-gilroy-bold text-[26px] xl:text-[32px] text-themecolor block mb-3 xl:mb-4">
                            About Us
                        </span>
                        <h2 className="text-[#202322] font-gilroy-bold text-[2.5rem] md:text-[3rem] xl:text-[3.5rem] 2xl:text-[4rem] leading-[4rem] md:leading-[5rem] lg:leading-[5.5rem] xl:leading-[5.813rem] mb-8 xl:mb-12">
                            We’re MyNikahNow App
                        </h2>
                        <p className="font-gilroy-medium text-xl text-[#909191]">
                            MyNikahNow is a unique and innovative app designed
                            specifically for Muslim couples who want to plan and
                            undertake their Nikah in a simple, fast, and
                            hassle-free way. Our mission is to provide a fully
                            halal-compliant alternative to civil marriage,
                            ensuring that the sacred tradition of Nikah is
                            accessible to all.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default About;
