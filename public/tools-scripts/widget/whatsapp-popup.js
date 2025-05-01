(function() {
    // Wait for DOM to be fully loaded
    function initWhatsAppWidget() {
        // Get parameters from script URL
        const scriptTag = document.currentScript || document.querySelector('script[src*="whatsapp-popup.js"]');
        const urlParams = new URLSearchParams(scriptTag.src.split('?')[1]);
        const phone = urlParams.get('phone');
        const position = urlParams.get('position') || 'right';
        const profileImage = urlParams.get('profileImage');
        const businessName = urlParams.get('businessName');
        const subHeading = urlParams.get('subHeading');
        const mainMessage = urlParams.get('mainMessage');
        const subMessage = urlParams.get('subMessage');
        const buttonOne = urlParams.get('buttonOne');
        const buttonTwo = urlParams.get('buttonTwo');
        const buttonThree = urlParams.get('buttonThree');

        // Create and inject CSS
        const style = document.createElement('style');
        style.textContent = `
            .whatsapp-float {
                position: fixed;
                bottom: 20px;
                ${position === 'right' ? 'right: 20px' : 'left: 20px'};
                background-color: #25d366;
                width: 60px;
                height: 60px;
                border-radius: 50%;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 99999;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .whatsapp-float:hover {
                transform: scale(1.1);
                box-shadow: 0 6px 16px rgba(0,0,0,0.2);
            }

            .whatsapp-float img {
                width: 60%;
                height: 60%;
                object-fit: contain;
            }

            .whatsapp-popup {
                display: none;
                position: fixed;
                bottom: 90px;
                ${position === 'right' ? 'right: 20px' : 'left: 20px'};
                width: 350px;
                height: auto;
                max-height: 80vh;
                background-color: white;
                border: 1px solid #ccc;
                border-radius: 10px;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                z-index: 1001;
                opacity: 0;
                transform: translateY(20px);
                transition: all 0.5s ease-in-out;
                flex-direction: column;
                pointer-events: none;
                visibility: hidden;
            }

            .whatsapp-popup.show {
                display: flex !important;
                opacity: 1;
                transform: translateY(0);
                pointer-events: auto;
                visibility: visible;
            }

            .whatsapp-popup-header {
                background-color: #075e54;
                color: white;
                padding: 15px;
                border-top-left-radius: 10px;
                border-top-right-radius: 10px;
                display: flex;
                align-items: center;
                font-weight: bold;
                position: relative;
            }

            .header-image {
                width: 40px;
                height: 40px;
                border-radius: 50%;
            }

            .header-text {
                margin-left: 10px;
            }

            .header-text h4 {
                margin: 0;
                font-size: 18px;
                color: white;
            }

            .header-text p {
                margin: 0;
                line-height: 1.2;
                font-size: 14px;
            }

            .whatsapp-popup-body {
                padding: 15px;
                flex: 0 1 auto;
                display: flex;
                flex-direction: column;
                max-height: calc(80vh - 180px);
                overflow-y: auto;
                background-image: url('https://bulkwise.in/tools/widget/wa-widget-background.png');
                background-repeat: repeat;
                background-position: center;
            }

            .message {
                background-color: #DCF8C6;
                padding: 10px;
                border-radius: 10px;
                margin: 5px 0;
                max-width: 80%;
                display: inline-block;
                color: black;
            }

            .message.bot {
                align-self: flex-start;
                background-color: #ffffff;
                border: 1px solid #ccc;
            }

            .button-container {
                display: flex;
                flex-direction: column;
                margin-top: auto;
                padding-top: 10px;
            }

            .button-container button {
                padding: 10px;
                border: none;
                background-color: #25d366;
                color: white;
                border-radius: 5px;
                cursor: pointer;
                margin-bottom: 5px;
                transition: background-color 0.3s;
            }

            .button-container button:hover {
                background-color: #1da85b;
            }

            .powered-by {
                flex: 0 0 auto;
                text-align: center;
                font-size: 12px;
                margin-bottom: 0;
                padding: 8px 0;
                background-color: #f8f9fa;
                border-top: 1px solid #eee;
                position: relative;
                z-index: 2;
                border-bottom-left-radius: 10px;
                border-bottom-right-radius: 10px;
            }

            .whatsapp-close {
                position: absolute;
                top: 10px;
                right: 15px;
                cursor: pointer;
                color: white;
                font-weight: bold;
            }
        `;
        document.head.appendChild(style);

        // Create WhatsApp elements
        const container = document.createElement('div');
        container.innerHTML = `
            <div class="whatsapp-float" id="whatsappIcon">
                <img src="https://cdn-icons-png.flaticon.com/512/124/124034.png" alt="WhatsApp">
            </div>

            <div class="whatsapp-popup" id="whatsappPopup">
                <div class="whatsapp-popup-header">
                    <img src="${profileImage}" alt="Profile" class="header-image">
                    <div class="header-text">
                        <h4>${businessName}</h4>
                        <p>${subHeading}</p>
                    </div>
                    <span class="whatsapp-close" id="whatsappClose">&times;</span>
                </div>
                <div class="whatsapp-popup-body">
                    <div class="message bot">${mainMessage}</div>
                    <div class="message bot">${subMessage}</div>
                    <div class="button-container">
                        <button onclick="window.open('https://wa.me/${phone}?text=${encodeURIComponent(buttonOne)}', '_blank')">${buttonOne}</button>
                        <button onclick="window.open('https://wa.me/${phone}?text=${encodeURIComponent(buttonTwo)}', '_blank')">${buttonTwo}</button>
                        <button onclick="window.open('https://wa.me/${phone}?text=${encodeURIComponent(buttonThree)}', '_blank')">${buttonThree}</button>
                    </div>
                </div>
                <div class="powered-by">
                     
                </div>
            </div>
        `;

        // Add elements to page
        if (document.body) {
            document.body.appendChild(container);
        } else {
            document.addEventListener('DOMContentLoaded', function() {
                document.body.appendChild(container);
            });
        }

        // Add event listeners
        const whatsappIcon = container.querySelector('#whatsappIcon');
        const whatsappPopup = container.querySelector('#whatsappPopup');
        const whatsappClose = container.querySelector('#whatsappClose');

        function closePopup() {
            whatsappPopup.classList.add('hide');
            setTimeout(function() {
                whatsappPopup.classList.remove('show');
                whatsappPopup.classList.remove('hide');
            }, 500);
        }

        whatsappIcon.addEventListener('click', function() {
            if (whatsappPopup.classList.contains('show')) {
                closePopup();
            } else {
                whatsappPopup.classList.add('show');
                whatsappPopup.classList.remove('hide');
            }
        });

        whatsappClose.addEventListener('click', closePopup);
    }

    // Check if DOM is already loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initWhatsAppWidget);
    } else {
        initWhatsAppWidget();
    }
})(); 