import React from 'react';

export const MailChimp = () => {

    return (
        <div>
            <div id="mc_embed_shell">
                <div id="mc_embed_signup">
                    <form
                        action="https://mynikahnow.us9.list-manage.com/subscribe/post?u=eb3d0044cb556115fc6b9c235&id=f19b294edf&f_id=000908e1f0"
                        method="post"
                        id="mc-embedded-subscribe-form"
                        name="mc-embedded-subscribe-form"
                        className="validate"
                        target="_self"
                        noValidate
                    >
                        <div id="mc_embed_signup_scroll">
                            <div className="indicates-required">
                                <span className="asterisk">*</span> indicates required
                            </div>
                            <div className="mc-field-group">
                                <label htmlFor="mce-EMAIL">
                                Email Address <span className="asterisk">*</span>
                                </label>
                                <input type="email" name="EMAIL" className="required email" id="mce-EMAIL" required="" value="" />
                                <span id="mce-EMAIL-HELPERTEXT" className="helper_text">
                                Enter your email
                                </span>
                            </div>
                            <div className="mc-field-group">
                                <label htmlFor="mce-FNAME">First Name</label>
                                <input type="text" name="FNAME" className="text" id="mce-FNAME" value="" />
                            </div>
                            <div className="mc-field-group">
                                <label htmlFor="mce-LNAME">Last Name</label>
                                <input type="text" name="LNAME" className="text" id="mce-LNAME" value="" />
                            </div>
                            <div className="mc-field-group">
                                <label htmlFor="mce-PHONE">Phone Number</label>
                                <input type="text" name="PHONE" className="REQ_CSS" id="mce-PHONE" value="" />
                            </div>
                            <div id="mce-responses" className="clear foot">
                                <div className="response" id="mce-error-response" style={{ display: 'none' }}></div>
                                <div className="response" id="mce-success-response" style={{ display: 'none' }}></div>
                            </div>
                            <div aria-hidden="true" style={{ position: 'absolute', left: '-5000px' }}>
                                {/* Real people should not fill this in and expect good things - do not remove this or risk form bot signups */}
                                <input type="text" name="b_eb3d0044cb556115fc6b9c235_f19b294edf" tabIndex="-1" value="" />
                            </div>
                            <div className="optionalParent">
                                <div className="clear foot">
                                    <input type="submit" name="subscribe" id="mc-embedded-subscribe" className="button" value="Subscribe" />
                                    {/* <p className="brandingLogo" style={{ margin: '0px auto' }}>
                                        <a href="http://eepurl.com/ic_HQT" title="Mailchimp - email marketing made easy and fun">
                                        <img
                                            src="https://eep.io/mc-cdn-images/template_images/branding_logo_text_dark_dtp.svg"
                                            alt="referral badge"
                                        />
                                        </a>
                                    </p> */}
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
}

export default MailChimp;
