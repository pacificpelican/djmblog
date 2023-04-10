<?php
require '../../post/connect.php';
include '../../post/postHeader.php';

$OWNER_NAME = "Daniel McKeown";
$SITE_NAME = "DanMcKeown.info";
?>

<div id="privacy">
			<Menu id="theMneu" />
			<h4 className="parrotHeader">Privacy</h4>
			<main id="privacy" class="site_info_pages terms_and_conditions terms_of_use">
				<h4><a href="javascript:history.back()">←</a></h4>
				<h1>Privacy Policy ("Privacy")</h1>
				<p>Last updated: Feb. 5, 2022</p>
				<p><?php echo $OWNER_NAME ?> ("us", "we", or "our") operates the <?php echo $SITE_NAME ?> website (the "Service").</p>
				<p>This page informs you of our policies regarding the collection, use and disclosure of Personal Information when you use our Service.</p>
				<p>We will not use or share your information with anyone except as described in this Privacy Policy.</p>
				<p>We use your Personal Information for providing and improving the Service. By using the Service, you agree to the collection and use of information in accordance with this policy. Unless otherwise defined in this Privacy Policy, terms used in this Privacy Policy have the same meanings as in our Terms and Conditions, accessible at <?php echo $SITE_NAME ?>/terms</p>
				<p><strong>Information Collection And Use</strong></p>
				<p>While using our Service, we may ask you to provide us with certain personally identifiable information that can be used to contact or identify you. Personally identifiable information may include, but is not limited to, your name, email, and other information ("Personal Information").</p>
				<p>Our site or parts of our site may be hosted on third party servers over which the owenrs have no control, and our site will not sanction but cannot promise to prevent disclosure of information to third parties.</p>
				<p><strong>Payment Info</strong></p>
				<p>Making payments if needed on the site may be accomplished with Stripe, a 3rd party payments system [https://stripe.com/].  User credit card numbers are sent over an SSL connection to Stripe and are not stored in the site's database.</p>
				<p><strong>Data Deletion</strong></p>
				<p>You may request the deletion of your data on the site.  If you would like your data deleted please contact the site at help@djmblog.com and we will work with you to remove all of your data.</p>
				<p><strong>Cookies</strong></p>
				<p>Cookies are files with small amount of data, which may include an anonymous unique identifier. Cookies are sent to your browser from a web site and stored on your computer's hard drive.</p>
				<p>We may use "cookies" to collect information. You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent. However, if you do not accept cookies, you may not be able to use some portions of our Service.</p>
				<p><strong>Chat/Messages/Posts/Comments</strong></p>
				<p>You may not use hateful/incendiary/threatening or otherwise unacceptable language or communication on the site.  Any violation of this policy (at sole discretion of owner) could lead to bans, data erasure and possibly reporting to responsible third parties.  Our site is a place that seeks to welcome people of any race, age [who are old enough as resricted elsewhere], ethnicity, national origin, belief, sexual orientation, gender identification, disabilty/ability, and any other reasonable category.  Any violation of this is unacceptable and will not be tolerated.</p>
				<p>Public Message content may be read, scanned, deleted or altered at the sole discretion of site owner.  User messages sent to other users may remain available to other users as their inbox copy.</p>
				<p><strong>File Uploads</strong></p>
				<p>User file uploads may be scanned and/or deleted  Users may only upload files that they own the rights to.</p>
				<p><strong>Service Providers</strong></p>
				<p>We may employ third party companies and individuals to facilitate our Service, to provide the Service on our behalf, to perform Service-related services or to assist us in analyzing how our Service is used.</p>
				<p>These third parties have access to your personal Information only to perform these tasks on our behalf and are regulated by their own privacy policies.</p>
				<p><strong>Security</strong></p>
				<p>The security of your Personal Information is important to us, but remember that no method of transmission over the Internet, or method of electronic storage is 100% secure. While we strive to use commercially acceptable means to protect your Personal Information, we cannot guarantee its absolute security.</p>
				<p><strong>Links To Other Sites</strong></p>
				<p>Our Service may contain links to other sites that are not operated by us. If you click on a third party link, you will be directed to that third party's site. We strongly advise you to review the Privacy Policy of every site you visit.</p>
				<p>We have no control over, and assume no responsibility for the content, privacy policies or practices of any third party sites or services.</p>
				<p><strong>Disclosure of Information</strong></p>
				<p>Our service respects user privacy and will seek to defend it but may need to turn certain information over to comply with valid court orders.</p>
				<p><strong>Children's Privacy</strong></p>
				<p>Our Service does not address anyone under the age of 18 ("Children").</p>
				<p>We do not knowingly collect personally identifiable information from children under 18. If you are a parent or guardian and you are aware that your Child has provided us with Personal Information, please contact us. If we discover that a Child under 18 has provided us with Personal Information, we will delete such information from our servers in an expeditious fashion.</p>
				<p><strong>Changes To This Privacy Policy</strong></p>
				<p>We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page.</p>
				<p>You are advised to review this Privacy Policy periodically for any changes. Changes to this Privacy Policy are effective when they are posted on this page.</p>
				<p><strong>Contact Us</strong></p>
				<p>If you have any questions about this Privacy Policy, please contact us at <a href="https://pacificio.com/contact">https://pacificio.com/contact</a> or through the <?php echo $SITE_NAME ?> web site.</p>
			</main>
			<style>
				:root {
					--uiFonts: "Ubuntu Mono", "Inconsolata", "Anonymous Pro", "Hack", Menlo,
						monospace;
					--serifFonts: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
					--contentFonts: "Lato", "Open Sans", "Lucida Grande", "Ubuntu Sans",
						"Segoe UI", "Roboto", Helvetica, sans-serif;
					--displayFonts: "Gentona", "Baufra", Helvetica, sans-serif;
					--monoFonts: "Anonymous Pro", "Hack", "Fira Sans", "Inconsolata",
						monospace;
					--textFonts: "Calluna", "Caslon", "Garamond" serif;
					--courierFonts: 'Courier New', Courier, monospace;
				}
				html {
          box-sizing: border-box;
        }
				div#parrotsLoginForm, #theMenu {
					font-family: var(--courierFonts, monospace);
					background: LavenderBlush;
					margin-left: calc(1vw + 5pt);
				}
				div#parrotsLoginForm input {
					width: calc(12pt + 8vw);
					height: calc(6pt + 3vh);
					font-size: calc(1.2rem);
				}
				div#loginParrots {
					margin-left: calc(1vw + 5pt);
				}
				#parrotHeader {
					font-family: var(--serifFonts, serif);
				}
			</style>
		</div>