<div class="gpt-container">
    <div class="gpt-playground-column gpt-playground-column-left">
        <style>
            .grid-item {
                background-color: #ffffff;
                padding: 15px;
                text-align: center;
                font-size: 13px;
                border-radius: 6px;
                box-shadow: rgba(50, 50, 93, 0.25) 0px 1px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;
                width: 36%;
                display: inline-block;
                margin: 7px 5px;
                cursor: pointer;
            }
        </style>

        <style>
            .multiselect-container {
                position: relative;
                display: inline-block;
            }

            .multiselect-selectbox {
                display: flex;
                align-items: center;
                justify-content: space-between;
                border: 1px solid #ccc;
                border-radius: 4px;
                padding: 6px 12px;
                width: 270px;
                cursor: pointer;
                background-color: #fff;
            }

            .multiselect-selectbox:hover {
                border-color: #888;
            }

            .multiselect-selectbox .selected-values {
                flex-grow: 1;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;
            }

            .multiselect-selectbox .caret-icon {
                margin-left: 10px;
            }

            .multiselect-options {
                position: absolute;
                z-index: 1;
                top: 100%;
                left: 0;
                right: 0;
                background-color: #fff;
                border: 1px solid #ccc;
                border-radius: 4px;
                max-height: 200px;
                overflow-y: auto;
                overflow-x: hidden;
                padding: 6px 0;
                display: none; /* Hide the options by default */
            }

            .multiselect-option {
                display: flex;
                align-items: center;
                padding: 4px 12px;
                cursor: pointer;
            }

            .multiselect-option:hover {
                background-color: #f5f5f5;
            }

            .multiselect-option input[type="checkbox"] {
                margin-right: 8px;
            }

            .multiselect-searchbox {
                padding: 6px 12px;
                border: 1px solid #ccc;
                border-radius: 4px;
                margin-bottom: 6px;
                width: 100%;
            }

            .multiselect-options.show {
                display: block !important;
            }
            /*.grid-item {
                display: block;
            }*/

            .output-box-copy-btn {
                position: relative;
                top: -5px;
                cursor: pointer;
            }

            .option-short-description span{
                display: none;
            }
            .option-short-description {
                margin-top: 10px;
                padding: 10px;
                background-color: #f1f1f1;
                border: 1px solid #ccc;
                border-radius: 4px;
            }
            .option-short-description .description-text {
                font-size: 14px;
                line-height: 1.5;
                margin-bottom: 10px;
            }

        </style>
        <div class="multiselect-container">
            <div class="multiselect-selectbox">
                <div class="selected-values">Select options</div>
                <div class="caret-icon">&#9662;</div>
            </div>
            <div class="multiselect-options">
                <input type="text" class="multiselect-searchbox" placeholder="Search options">
                <div class="multiselect-option">
                    <input type="checkbox" value="Answers">
                    Answers
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="Blog">
                    Blog
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="Business">
                    Business
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="Classification">
                    Classification
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="Coaching">
                    Coaching
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="Conversation">
                    Conversation
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="Domain">
                    Domain
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="Email">
                    Email
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="Facebook">
                    Facebook
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="Fashion">
                    Fashion
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="Generation">
                    Generation
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="Google">
                    Google
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="Instagram">
                    Instagram
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="Keyword">
                    Keyword
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="LinkedIn">
                    LinkedIn
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="Marketing">
                    Marketing
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="Product">
                    Product
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="Real Estate">
                    Real Estate
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="Review">
                    Review
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="Sales">
                    Sales
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="SEO">
                    SEO
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="Social Media">
                    Social Media
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="Summarization">
                    Summarization
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="TikTok">
                    TikTok
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="Transformation">
                    Transformation
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="Translation">
                    Translation
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="Twitter">
                    Twitter
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="Video">
                    Video
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="YouTube">
                    YouTube
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="Wikipedia">
                    Wikipedia
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="WooCommerce">
                    WooCommerce
                </div>

                <div class="multiselect-option">
                    <input type="checkbox" value="WordPress">
                    WordPress
                </div>

            </div>
        </div>
        <div class="grid-container">



            <div class="grid-item" cats="Generation, Marketing"prompt="Use your expertise as a content marketing consultant to devise a strategy that suits [YOUR BUSINESS]'s content marketing needs.">Content Marketing Strategy Generator</div>

            <div class="grid-item" cats="Generation, SEO, Google" prompt="Provide your expertise as an SEO consultant and propose a meta description that is optimized for search engines on [YOUR WEBSITE].">SEO Meta Description Generator</div>

            <div class="grid-item" cats="Generation, SEO, Google, Keyword" prompt="Your task as a digital marketing consultant is to generate a list of relevant keywords for a website or blog post about [YOUR TOPIC].">Google Keyword Research Generator
            </div>

            <div class="grid-item" cats="Generation, Marketing, Social Media" prompt="Offer your expertise as a social media marketing consultant and develop a social media marketing plan for [YOUR BUSINESS].">Social Media Marketing Plan Generator
            </div>

            <div class="grid-item" cats="Generation, SEO, Google" prompt="In your capacity as a digital marketing consultant, develop a list of effective headlines for a Google AdWords ad campaign for [YOUR BUSINESS].">Google AdWords Headline Generator
            </div>
            <div class="grid-item" cats="Generation, Product, WooCommerce" prompt="In your capacity as a product writer, produce a comprehensive comparison of [YOUR PRODUCT] and its closest competitors.">
                WooCommerce Product Comparison Generator
            </div>

            <div class="grid-item" cats="Generation, Product, WooCommerce" prompt="Your task is to generate an effective product upsell for [YOUR PRODUCT].">
                WooCommerce Product Upsell Generator

            </div>

            <div class="grid-item" cats="Coaching" prompt="Develop a list of 10 healthy eating habits for a better diet.">
                Nutrition Coach

            </div>

            <div class="grid-item" cats="Generation, Social Media, Facebook" prompt="In your capacity as a copywriter, craft a catchy headline that entices customers to take advantage of the sale on [YOUR PRODUCT] through Facebook.">
                Facebook Ad Headline Generator

            </div>

            <div class="grid-item" cats="Generation, Social Media" prompt="Your assignment is to analyze the advantages and disadvantages of remote work and present your findings.">
                Reddit Discussion

            </div>

            <div class="grid-item" cats="Generation, Social Media" prompt="Generate a compelling submission for Hacker News that discusses the impact of AI on the job market in the coming years.">
                Hackernews Submission

            </div>

            <div class="grid-item" cats="Generation, Conversation, LinkedIn, Social Media" prompt="Hi, I'm John Doe, a software engineer. Your tech industry experience caught my eye, and I'd love to connect with you to learn more. Can you share more about your current projects and experience?">
                Networking

            </div>

            <div class="grid-item" cats="Coaching, Generation, LinkedIn" prompt="As a software developer with 5 years of experience, I'm eager to take my career to the next level. Can you share some tips on how to do that?">
                Career Development

            </div>

            <div class="grid-item" cats="Generation, LinkedIn, Coaching" prompt="Submit your resume for optimization. Include your education and work experience, with a focus on your skills in Python programming">
                Resume Optimization
            </div>

            <div class="grid-item" cats="Generation, Conversation, LinkedIn, Social Media" prompt="Write a personalized LinkedIn intro for John Doe, a Software Engineer with a wealth of experience in the Tech industry, to showcase his unique abilities and achievements.">
                Networking Introduction

            </div>

            <div class="grid-item" cats="Generation, Conversation, LinkedIn, Social Media" prompt="John Doe has my full endorsement for his outstanding skills in Product Management, Leadership, and Communication. He is a valuable asset to any team.">
                Endorsement Language

            </div>

            <div class="grid-item" cats="Generation, Social Media, LinkedIn" prompt="Generate a LinkedIn post that highlights my recent promotion and the skills I used to achieve it:">
                LinkedIn post generator

            </div>

            <div class="grid-item" cats="Generation, Social Media, Twitter" prompt="Generate a Twitter thread about the importance of mental health:">
                Twitter thread generator

            </div>

            <div class="grid-item" cats="Generation, Social Media, Instagram" prompt="Generate a captivating caption for a stunning sunset photo on Instagram:">
                Instagram caption generator

            </div>

            <div class="grid-item" cats="Generation, Social Media, Facebook" prompt="Develop a social media post highlighting the negative effects of smoking.">
                Post for Facebook

            </div>

            <div class="grid-item" cats="Classification, Social Media, Twitter, Summarization" prompt="Create a tweet summarizing the main points of a news article. Provide the article's title.

Article Title: 'New study shows link between air pollution and heart disease'">
                Tweet summary of news article

            </div>

            <div class="grid-item" cats="Generation, Blog, Product, WooCommerce" prompt="Develop a title for a blog post that introduces The Smart Vacuum, a revolutionary cleaning device equipped with voice control and mapping technology.">
                Blog post about a new product

            </div>

            <div class="grid-item" cats="Generation, Email, Sales, Product" prompt="Generate excitement and sales with an email promoting the Portable Wireless Charger sale, offering a 20% discount. Subject line: 'Charge up for less - our Portable Wireless Chargers are on sale now!'">
                Email to promote a sale

            </div>

            <div class="grid-item" cats="Generation, Social Media, Product" prompt="Create a social media message that promotes The Classic Tote, a high-quality and trendy leather tote bag that takes your style to the next level. Caption it as 'Make a fashion statement with The Classic Tote.'">
                Social media post for a fashion product

            </div>

            <div class="grid-item" cats="Generation, Instagram, Social Media" prompt="Generate a catchy and engaging caption for an Instagram post. Post: A photo of a delicious meal">
                Instagram Caption Generator

            </div>

            <div class="grid-item" cats="Generation, Instagram, Social Media" prompt="Generate a list of relevant hashtags to use in your Instagram posts. Topic: Fitness">
                Instagram Hashtag Generator

            </div>

            <div class="grid-item" cats="Generation, Blog" prompt="Craft an engaging headline for a blog post that provides practical time management tips, such as 'Time Flies: The Ultimate Guide to Time Management for Productivity.'">
                Blog Post Title Generator

            </div>

            <div class="grid-item" cats="Generation, Blog" prompt="Generate an engaging introduction for a blog post. Topic: The benefits of meditation">
                Blog Post Introduction Generator

            </div>

            <div class="grid-item" cats="Generation, Blog" prompt="Develop a final paragraph for a blog post that emphasizes the many advantages of eating organic food, including improved nutrition, better taste, and the absence of harmful chemicals.">
                Blog Post Conclusion Generator

            </div>

            <div class="grid-item" cats="Generation, Blog, WordPress" prompt="Develop a compelling and memorable title for a WordPress blog post on social media marketing, such as 'Social Media Mastery: A Step-by-Step Guide to Marketing Like a Pro.">
                WordPress Blog Post Title Generator

            </div>

            <div class="grid-item" cats="Generation, Blog, WordPress" prompt="Generate an engaging introduction for a WordPress blog post about 'Email Marketing'">
                WordPress Blog Post Introduction Generator

            </div>

            <div class="grid-item" cats="Generation, Social Media, Marketing" prompt="Create a compelling social media post that introduces our latest product, an eco-friendly water bottle that's perfect for an active and sustainable lifestyle">
                Social Media Post Generator

            </div>

            <div class="grid-item" cats="Generation, Social Media, Marketing" prompt="Generate a caption for a social media post about a new restaurant opening.">
                Social Media Caption Generator

            </div>

            <div class="grid-item" cats="Generation, Product" prompt="Generate a product listing for an e-commerce website. Product: Smartwatch">
                E-commerce Product Listing Generator

            </div>

            <div class="grid-item" cats="Generation, Product" prompt="Generate a detailed product description for an e-commerce website. Product: Electric Bike">
                E-commerce Product Description Generator

            </div>

            <div class="grid-item" cats="Generation, SEO, Blog" prompt="Discover inner peace with our range of yoga classes - an SEO-friendly title for a website page.">
                SEO Title Generator

            </div>

            <div class="grid-item" cats="Answers, Generation, Conversation" prompt="I am a highly intelligent question answering bot. If you ask me a question that is rooted in truth, I will give you the answer. If you ask me a question that is nonsense, trickery, or has no clear answer, I will respond with 'Unknown'.

	Q: What is human life expectancy in the United States?
	A:">
                Question & Answer

            </div>

            <div class="grid-item" cats="Generation" prompt="Make the sentence 'She chose not to go to the market' grammatically correct in standard English.">
                Grammar correction

            </div>

            <div class="grid-item" cats="Generation" prompt="Jupiter is a very large planet that's fifth from the Sun. It's much bigger than all the other planets in the Solar System combined. People have known about Jupiter for a very long time and it's really bright in the night sky.">
                Summarize for a 2nd grader

            </div>

            <div class="grid-item" cats="Generation, Translation" prompt="Could you translate 'What rooms do you have available?' into French, Spanish, and Japanese?">
                English to other languages

            </div>

            <div class="grid-item" cats="Generation" prompt="A table summarizing the fruits from Goocrux:">
                Parse unstructured data
            </div>

            <div class="grid-item" cats="Classification" prompt="The following is a list of companies and the categories they fall into:
	Apple, Facebook, Fedex
	Apple	Category:">
                Classification

            </div>

            <div class="grid-item" cats="Generation" prompt="Convert movie titles into emoji:">
                Movie to Emoji
            </div>

            <!-- 	<div class="grid-item" cats="Classification" prompt="This is an advanced prompt for detecting sentiment. It allows you to provide it with a list of status updates and then provide a sentiment for each one.

            Status updates:

            I love this product!
            This product is terrible.">
                    Advanced tweet classifier

                </div> -->

            <div class="grid-item" cats="Classification, Keyword" prompt="Extract keywords from this text:">
                Keywords
            </div>

            <div class="grid-item" cats="Answers, Classification, Conversation, Generation" prompt="Q: Who is George Lucas?
	A:
	Q: Can you tell me about Batman?
	A:
	Q: What is Devz9?
	A:
	Q: What does torsalplexity mean?
	A:
	Q: Who is Batman?
	A:">
                Factual answering

            </div>

            <!-- 	<div class="grid-item" cats="Generation" prompt="Write a creative ad for the following product to run on Facebook aimed at parents:

            Product: Learning Room is a virtual environment to help students from kindergarten to high school excel in school.">
                    Ad from product description

                </div> -->

            <div class="grid-item" cats="Generation" prompt="Product description: ">
                Product name generator

            </div>


            <div class="grid-item" cats="Generation" prompt="A two-column spreadsheet of top vitamin frout and the year of release:

Title |  Year of release">
                Spreadsheet creator

            </div>

            <div class="grid-item" cats="Generation" prompt="List 10 science fiction books:">
                Science fiction book list maker

            </div>

            <div class="grid-item" cats="Classification" prompt="Determine the sentiment of the following tweet: 'The weather today is terrible.'">
                Tweet classifier

            </div>

            <div class="grid-item" cats="Generation" prompt="Extract the airport codes from this text:

Text: 'I want to fly from Los Angeles to Miami.'
Airport codes: LAX, MIA

Text: 'I want to fly from Orlando to Boston'
Airport codes:">
                Airport code extractor

            </div>

            <div class="grid-item" cats="Generation
" prompt="Dear Kelly,

I hope this letter finds you well. I wanted to express my gratitude for the opportunity to attend the seminar with you and to catch up afterwards. Jane's talk was quite good, and it was wonderful to see you again.

Also, thank you for the book. I appreciate the thoughtful gift. As promised, here is my address: 2111 Ash Lane, Crestview CA 92002.

Best,
Maya">
                Extract contact information
            </div>

            <div class="grid-item" cats="Conversation, Generation" prompt="You: What have you been up to?
Friend: Watching old movies.
You: Did you watch anything interesting?
Friend:">
                Friend chat

            </div>

            <div class="grid-item" cats="Generation" prompt="The CSS code for a color like a blue sky at dusk:

background-color: #">
                Mood to color

            </div>

            <div class="grid-item" cats="Generation" prompt="Create an analogy for this phrase:

Questions are arrows in that:">
                Analogy maker

            </div>

            <div class="grid-item" cats="Generation, Translation
" prompt="Topic: Abandoned House
Two-Sentence Horror Story: I was exploring the abandoned house when I heard someone whisper my name. It wasn't until I got home and looked through my pictures that I realized I wasn't alone.">
                Micro horror story creator
            </div>

            <div class="grid-item" cats="Generation, Translation" prompt="Convert this from first-person to third person (gender female):

I decided to make a movie about Ada Lovelace.">
                Third-person converter

            </div>

            <div class="grid-item" cats="Generation, Summarization
" prompt="During the meeting, Tom reported that profits had increased by 50%. Jane informed the group that new servers were now online. Kjel mentioned that more time was needed to fix the software, to which Jane offered her assistance. Lastly, Parkman stated that beta testing was almost completed.">
                Notes to summary
            </div>

            <div class="grid-item" cats="Generation" prompt="Generate some concepts that blend virtual reality and exercise.">
                VR fitness idea generator

            </div>

            <div class="grid-item" cats="Generation" prompt="Create an outline for an essay about Nikola Tesla and his contributions to technology:">
                Essay outline

            </div>

            <div class="grid-item" cats="Generation" prompt="Write a recipe based on these ingredients and instructions:

Frito Pie

Ingredients:
Fritos
Chili
Shredded cheddar cheese
Sweet white or red onions, diced small
Sour cream

Instructions:">
                Recipe creator

            </div>

            <div class="grid-item" cats="Generation, Conversation" prompt="Human: Hi there, what are your capabilities?
AI: Nice to meet you! I can assist you with a wide range of tasks, from answering questions to completing complex calculations. What can I help you with today?">
                Chat

            </div>

            <div class="grid-item" cats="Generation, Conversation" prompt="Joe is a chatbot that reluctantly answers questions with sarcastic responses:

You: What's the capital of France?
Joe: Are you seriously asking me that? It's Paris, obviously. Do I look like a geography teacher to you?
You: How do I fix a leaky faucet?
Joe: Wow, you're really helpless, aren't you? Try tightening the screw or calling a plumber. Or just live with the constant dripping. Whatever works for you.
You: Who won the World Series in 2020?
Joe: Are you kidding me? It was the Los Angeles Dodgers. How do you not know this already?
You: What's the weather like today?
Joe: Oh, let me guess, you can't be bothered to check your phone or look outside? It's sunny and 75 degrees. Happy now?
You: Can you tell me a joke?
Joe: Sure, I'll tell you the same one I tell everyone. Why don't scientists trust atoms? Because they make up everything. Hilarious, right?">
                Marv the sarcastic chat bot

            </div>

            <div class="grid-item" cats="Generation" prompt="Create a numbered list of turn-by-turn directions from this text:

Go south on 95 until you hit Sunrise boulevard then take it east to us 1 and head south. Tom Jenkins bbq will be on the left after several miles.">
                Turn by turn directions

            </div>

            <div class="grid-item" cats="Generation" prompt="Write a restaurant review based on these notes:

Name: The Blue Wharf
Lobster great, noisy, service polite, prices good.

Review:">
                Restaurant review creator
            </div>

            <div class="grid-item" cats="Generation" prompt="Provide a list of 5 essential pieces of information to keep in mind when researching Ancient Rome.">
                Create study notes

            </div>

            <div class="grid-item" cats="Generation" prompt="Create a list of 8 questions for my interview with a science fiction author:">
                Interview questions

            </div>

            <div class="grid-item" cats="Generation, Product, Review" prompt="Write a product review for a new tech product. Product: The Smartwatch Pro - a high-end smartwatch with advanced fitness tracking and mobile payment capabilities.

Review title: ">
                Product review for a tech product

            </div>

            <div class="grid-item" cats="Generation, Product, WooCommerce
" prompt="Create a brief summary for a Shopify product. Product: Bluetooth Speaker.">
                WooCommerce Short Description Generator
            </div>

            <div class="grid-item" cats="Generation, Product, WooCommerce" prompt="Generate a long description for a WooCommerce product. Product: KitchenAid Stand Mixer">
                WooCommerce Long Description Generator

            </div>

            <div class="grid-item" cats="Generation, Product" prompt="Develop a recipe that incorporates the use of a recently introduced vegan protein powder as an ingredient.">
                Recipe using a new food product

            </div>

            <div class="grid-item" cats="Generation, Product, WooCommerce" prompt="Generate a product bundle for a WooCommerce product. Product: Fitness Tracker">
                WooCommerce Product Bundle Generator

            </div>

            <div class="grid-item" cats="Generation, Product, WooCommerce" prompt="Rewritten prompt: 'Create a feature for a Water Bottle product in a WooCommerce store.'">
                WooCommerce Product Attribute Generator

            </div>

            <div class="grid-item" cats="Generation, Product, WooCommerce" prompt="Generate product specifications for a WooCommerce product. Product: Smart Watch">
                WooCommerce Product Specification Generator

            </div>

            <div class="grid-item" cats="Generation, Product, WooCommerce" prompt="Generate the benefits of a WooCommerce product. Product: Yoga Mat">
                WooCommerce Product Benefit Generator

            </div>

            <div class="grid-item" cats="Generation, Product, WooCommerce, Keyword" prompt="Create a list of keywords to improve SEO for an electric toothbrush product on WooCommerce.">
                WooCommerce Product Keyword Generator

            </div>

            <div class="grid-item" cats="Generation, Product, WooCommerce" prompt="Generate a catchy tagline for a WooCommerce product. Product: Portable Charger">
                WooCommerce Product Tagline Generator

            </div>

            <div class="grid-item" cats="Generation, Product, WooCommerce" prompt="Generate a customer testimonial for a WooCommerce product. Product: Yoga Mat">
                WooCommerce Product Testimonial Generator

            </div>

            <div class="grid-item" cats="Generation, Social Media, Facebook" prompt="Create a detailed description for a LinkedIn Live event. Event Name: Expert Panel on Social Media Marketing.">
                Facebook Event Description Generator

            </div>

            <div class="grid-item" cats="Generation, Social Media, Facebook" prompt="Write a detailed and engaging description for a Facebook group dedicated to discussing and sharing tips on gardening.">
                Facebook Group Description Generator

            </div>

            <div class="grid-item" cats="Generation, Twitter, Social Media" prompt="Craft a distinctive bio for your Twitter profile that incorporates the following keywords: social media, marketing, entrepreneur.">
                Twitter Bio Generator

            </div>

            <div class="grid-item" cats="Generation, Twitter, Social Media, Product" prompt="Create a tweet to promote the brand or product of XYZ Coffee in a catchy and engaging way on Twitter.">
                Twitter Engaging Tweet Generator

            </div>

            <div class="grid-item" cats="Generation, Twitter, Social Media" prompt="Generate a list of relevant hashtags to use in your tweets. Topic: Travel">
                Twitter Hashtag Generator

            </div>

            <div class="grid-item" cats="Generation, Twitter, Social Media" prompt="Craft a tweet that motivates users to share and participate with your content.">
                Twitter Retweet Generator

            </div>

            <div class="grid-item" cats="Generation, SEO, Blog" prompt="Create a title tag for a webpage focused on providing SEO advice for small enterprises.">
                SEO Title Tag Generator

            </div>

            <div class="grid-item" cats="Generation, SEO, Keyword, Google" prompt="Produce a keyword inventory for an online store specializing in selling handmade soap.">
                SEO Keyword Research Generator

            </div>

            <div class="grid-item" cats="Generation, SEO, Google, Keyword" prompt="Generate a list of potential search queries for a topic about 'best coffee shops in New York City'">
                Google Search Query Generator
            </div>

            <div class="grid-item" cats="Generation, SEO, Google, Keyword" prompt="Create a list of search recommendations for the keyword 'coffee'">
                Google Search Suggestions Generator
            </div>

            <div class="grid-item" cats="Generation, Summarization, Wikipedia" prompt="Compose a brief overview of the Wikipedia entry on the 'History of the Internet'">
                Wikipedia Article Summarizer

            </div>

            <div class="grid-item" cats="Generation, Fashion, Blog" prompt="Generate a blog post about the latest fashion trend: Chunky Sneakers">
                Fashion Blog Post Generator

            </div>

            <div class="grid-item" cats="Generation, Fashion" prompt="Provide a portrayal of a clothing ensemble in the Bohemian-Chic style.">
                Fashion Outfit Description Generator

            </div>

            <div class="grid-item" cats="Real Estate, Generation" prompt="Create a captivating listing depiction for a real estate asset with a 3-bedroom house situated in San Francisco.">
                Real Estate Listing Description Generator

            </div>

            <div class="grid-item" cats="Real Estate, Generation" prompt="Generate a description of a real estate neighborhood. Neighborhood: Beverly Hills">
                Real Estate Neighborhood Description Generator
            </div>

            <div class="grid-item" cats="Keyword, Wikipedia" prompt="Extract the key keywords from the Wikipedia article:

Nikola Tesla was a Serbian-American inventor, electrical engineer, mechanical engineer, and futurist best known for his contributions to the design of the modern alternating current (AC) electricity supply system. Born and raised in the Austrian Empire, Tesla studied engineering and physics in the 1870s without receiving a degree, gaining practical experience in the early 1880s working in telephony and at Continental Edison in the new electric power industry. In 1884 he emigrated to the United States, where he became a naturalized citizen. He worked for a short time at the Edison Machine Works in New York City before he struck out on his own. With the help of partners to finance and market his ideas, Tesla set up laboratories and companies in New York to develop a range of electrical and mechanical devices. His alternating current (AC) induction motor and related polyphase AC patents, licensed by Westinghouse Electric in 1888, earned him a considerable amount of money and became the cornerstone of the polyphase system which that company eventually marketed.">
                Wikipedia Article Keyword Extractor
            </div>

            <div class="grid-item" cats="Generation, Product, WooCommerce" prompt="Produce an inventory of attributes for a WooCommerce commodity showcasing a Smart Home Security Camera.">
                WooCommerce Product Feature List Generator

            </div>

            <div class="grid-item" cats="Email, Generation" prompt="Compose an opening statement for a formal email.">
                Professional Email Introduction
            </div>

            <div class="grid-item" cats="Email, Generation" prompt="Create a second email to follow-up on a previous message.">
                Follow-Up Email

            </div>

            <div class="grid-item" cats="Email, Generation" prompt="Craft a response to a customer service email addressing a product return inquiry.">
                Customer Service Reply
            </div>

            <div class="grid-item" cats="Email, Generation" prompt="Draft a job application email for the position of Marketing Manager.">
                Job Application Email

            </div>

            <div class="grid-item" cats="Generation, Domain" prompt="Produce five recommendations for a domain name for a coffee shop.">
                Domain Name Suggestion Tool

            </div>

            <div class="grid-item" cats="Generation, Domain" prompt="Create a slogan for the domain name 'CoffeeBuzz.com'">
                Domain Name Catchphrase Generator

            </div>

            <div class="grid-item" cats="Generation, Domain" prompt="Generate a tagline for the domain name 'CoffeeCorner.com'">
                Domain Name Tagline Generator

            </div>

            <div class="grid-item" cats="Coaching, Business" prompt="Produce guidance for commencing a business.">
                Advice Generator for Starting a Business
            </div>

            <div class="grid-item" cats="Coaching" prompt="Create recommendations for enhancing relationships.">
                Advice Generator for Improving Relationships

            </div>

            <div class="grid-item" cats="Generation, Marketing, Social Media" prompt="Develop a promotional campaign for social media marketing, promoting Eco-Friendly Water Bottles.">
                Social Media Ad Campaign Generator
            </div>


            <div class="grid-item" cats="Generation, Email, Marketing" prompt="Create an email marketing campaign for a Luxury Skincare Line product.">
                Email Marketing Campaign Generator

            </div>

            <div class="grid-item" cats="Generation, Marketing" prompt="Design a marketing landing page to promote a new Smart Thermostat product.">
                Marketing Landing Page Generator

            </div>

            <div class="grid-item" cats="Generation, Review, Product" prompt="Generate a review for a product. Product: Smart Home Security System">
                Product Review Generator

            </div>

            <div class="grid-item" cats="Generation, Review" prompt="Compose a critique for an Italian Bistro restaurant">
                Restaurant Review Generator

            </div>

            <div class="grid-item" cats="Generation, Sales, Email" prompt="Construct a sales email for a new product launch, highlighting the Solar Panel System">
                Sales Email Generator

            </div>

            <div class="grid-item" cats="Generation, Sales, Email" prompt="Generate a follow-up email after a sales call. Product: Fitness Tracking Watch">
                Sales Follow-Up Email Generator

            </div>

            <div class="grid-item" cats="Generation, Video, YouTube" prompt="Create an attention-grabbing title for a YouTube video that demonstrates a 10-minute morning yoga routine.">
                YouTube Video Title Generator

            </div>

            <div class="grid-item" cats="Generation, Video, YouTube" prompt="Generate a 10 minute youtube video description about: ">
                YouTube Video Description Generator

            </div>

            <div class="grid-item" cats="Coaching, Business" prompt="Generate 10 effective strategies for managing workload and achieving a better work-life balance in the business environment">
                Successful Business Coach

            </div>

            <div class="grid-item" cats="Coaching, Business" prompt="Create a list of 10 potential solutions for a given problem, including straightforward ideas, to aid in resolving the issue.

Problem: ">
                Business Growth Coach

            </div>

            <div class="grid-item" cats="Coaching, Business" prompt="Develop a list of 10 innovative strategies for overcoming a business challenge.

Problem: ">
                Business Strategist

            </div>



            <div class="grid-item" cats="Generation, Social Media, Facebook" prompt="Create an attention-grabbing caption for a Facebook post to promote the launch of a new electric bike product">
                Facebook Post Caption Generator

            </div>

            <div class="grid-item" cats="Business, Coaching" prompt="Generate a list of 10 potential solutions to a business challenge, including unconventional approaches.

Problem: ">
                Business Strategy Advisor

            </div>
        </div>

    </div>
    <?php
    $current_user_id = get_current_user_id();
    $current_user_avatar = get_avatar($current_user_id);
    echo '<div class="aiwa-user-avatar aiwa-hidden-important">'.$current_user_avatar.'</div>';
    ?>

    <div class="gpt-playground-column gpt-playground-column-middle">

        <div class="ai-writing-assistant-settings-panel">
            <ul class="nav nav-tabs" role="tablist" style="text-transform: uppercase">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tabs-prompt-based" data-id="promptBased-settings" role="tab"><span class="dashicons dashicons-media-document"></span><span class="setting-title"><?php echo __('Prompt Based', 'ai-writing-assistant'); ?></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tabs-chat" data-id="chat-settings" role="tab"><span class="dashicons dashicons-media-document"></span><span class="setting-title"><?php echo __('Chat', 'ai-writing-assistant'); ?></span></a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <form id="ai-settings-form">
                    <div class="tab-pane promptBased-settings active" data-id="promptBased-settings" role="tabpanel">
                        <div class="settings-item">
                            <div class="gpt-playground-prompt-container">
                                <label for="gpt-prompt-based" class="gpt-playground-label"><?php _e('Enter your prompt here:', 'ai-writing-assistant'); ?></label>
                                <textarea id="gpt-prompt-based" class="gpt-prompt-based" placeholder="Type your message here..."></textarea>

                                <div class="aiwa-generation-btn-section">

                                    <button id="aiwa-generation-prompt" class="aiwa-button" role="button" style="float: right;padding: 8px 10px;text-align: left;box-shadow: 1px 2px 3px rgba(0, 0, 0, 0.54);-webkit-box-shadow: 1px 2px 3px rgba(0, 0, 0, 0.54);-moz-box-shadow: 1px 2px 3px rgba(0, 0, 0, 0.54);text-transform: capitalize;">

                                        <span class="title"><?php _e('Generate', 'ai-writing-assistant'); ?></span>
                                        <span class="aiwa_spinner hide_spin"></span>
                                    </button>
                                    <span style="font-size: 13px;font-weight: normal;float: right;margin-right: 10px;margin-top: 10px;padding: 6px 4px;" class="empty-prompt badge badge-danger aiwa-hidden"><?php _e('Prompt can\'t be empty, please enter a prompt.', 'ai-writing-assistant'); ?></span>

                                </div>

                                <div class="gpt-playground-generated-output-section">
                                    <label for="gpt-playground-generated-output" class="gpt-playground-label" style="display: inline-block"><?php _e('Output:', 'ai-writing-assistant'); ?></label>
                                    <button class="output-box-copy-btn" title="Copy to clipboard">
                                        <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                                        </svg>
                                        <span style="color: #333;">Copy</span>
                                    </button>
                                    <textarea id="gpt-playground-generated-output" class="gpt-playground-generated-output" readonly style="height: 150px;" placeholder="<?php _e('Generated output contents', 'ai-writing-assistant'); ?>"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane chat-settings" data-id="chat-settings" role="tabpanel">
                        <div class="settings-item">

                            <div class="gpt-playground-chatbox">
                            </div>
                            <div class="gpt-playground-prompt-container">
                                <label for="gpt-playground-prompt" class="gpt-playground-label"><?php _e('Enter your prompt here:', 'ai-writing-assistant'); ?></label>
                                <textarea id="gpt-playground-prompt" class="gpt-playground-prompt" placeholder="<?php _e('Type your message here...', 'ai-writing-assistant'); ?>"></textarea>

                                <div class="aiwa-chat-btn-section">

                                    <button id="aiwa-send-prompt" class="aiwa-button" role="button" style="float: right;padding: 8px 10px;text-align: left;box-shadow: 1px 2px 3px rgba(0, 0, 0, 0.54);-webkit-box-shadow: 1px 2px 3px rgba(0, 0, 0, 0.54);-moz-box-shadow: 1px 2px 3px rgba(0, 0, 0, 0.54);text-transform: capitalize;">

                                        <span class="title"><?php _e('Send', 'ai-writing-assistant'); ?></span>
                                        <span class="aiwa_spinner hide_spin"></span>
                                    </button>
                                    <span style="font-size: 13px;font-weight: normal;float: right;margin-right: 10px;margin-top: 10px;padding: 6px 4px;" class="empty-prompt badge badge-danger aiwa-hidden"><?php _e('Prompt can\'t be empty, please enter a prompt.', 'ai-writing-assistant'); ?></span>

                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>


        </div>


    </div>
    <div class="gpt-playground-column gpt-playground-column-right">
        <h2 class="gpt-playground-heading"><?php _e('API Settings', 'ai-writing-assistant'); ?></h2>
        <div class="gpt-playground-settings-container">

            <div class="settings-item">
                <label for="aiwa-chatting-models"><?php _e('Models', 'ai-writing-assistant'); ?></label>
                <select id="aiwa-chatting-models" name="chatting_models">
                    <option value="gpt-3.5-turbo-instruct">gpt-3.5-turbo-instruct</option>
                    <option value="gpt-3.5-turbo" selected>gpt-3.5-turbo</option>
                    <option value="gpt-4">gpt-4</option>
                    <option value="text-curie-001">text-curie-001</option>
                    <option value="text-babbage-001">text-babbage-001</option>
                    <option value="text-ada-001">text-ada-001</option>
                </select>

                <div class="option-short-description">
                    <span class="description description-text" data-id="gpt-3.5-turbo-instruct"><?php _e('A highly advanced language model that can understand and generate natural language, providing sophisticated and nuanced responses.', 'ai-writing-assistant'); ?></span>
                    <span class="description description-text" data-id="gpt-3.5-turbo" style="display: block"><?php _e('An advanced language model capable of generating human-like text, with enhanced performance and efficiency compared to its predecessors.', 'ai-writing-assistant'); ?> </span>
                    <span class="description description-text" data-id="gpt-4"><?php _e('The next generation of language models, offering even more powerful and accurate text generation capabilities compared to previous versions.', 'ai-writing-assistant'); ?></span>
                    <span class="description description-text" data-id="text-curie-001"><?php _e('A language model designed to comprehend and produce human-like text, offering a balance between sophistication and efficiency.', 'ai-writing-assistant'); ?></span>
                    <span class="description description-text" data-id="text-babbage-001"><?php _e('An innovative language model focused on understanding and generating text, capable of providing intelligent and contextually relevant responses.', 'ai-writing-assistant'); ?></span>
                    <span class="description description-text" data-id="text-ada-001"><?php _e('A cutting-edge language model designed to understand and generate text with high accuracy, providing insightful and contextually appropriate answers.', 'ai-writing-assistant'); ?></span>
                </div>
            </div>

            <?php aiwa_api_settings(); ?>
        </div>
    </div>

    <input type="hidden" id="playground-placeholders" value="Write a short story about a group of astronauts who discover a habitable planet.,Create a menu for a Mexican-themed cocktail bar.,Write a poem about the beauty of the night sky.,Design a logo for a new fashion line.,Write a short story about a detective who solves a murder case in a small town.,Create a menu for a vegetarian Italian restaurant.,Write a poem about the ocean and its vastness.,Design a logo for a new pet grooming service.,Write a short story about a person who wakes up with no memory of their past.,Create a menu for a Thai fusion restaurant.,Write a poem about the joys of friendship.,Design a logo for a new home cleaning service.,Write a short story about a young inventor who creates a time machine.,Create a menu for a vegan sushi restaurant.,Write a poem about the beauty of the natural world.,Design a logo for a new mobile game.,Write a short story about a family who moves to a new town and discovers a dark secret.,Create a menu for a vegan bakery.,Write a poem about the power of love.,Design a logo for a new meditation app.,Write a short story about a person who can communicate with animals.,Create a menu for a plant-based fast food restaurant.,Design a logo for a new online tutoring service.,Write a short story about a group of teenagers who uncover a government conspiracy.,Create a menu for a vegan BBQ restaurant.,Write a poem about the magic of winter.,Design a logo for a new travel app.,Write a short story about a person who wakes up in a world where everyone has superpowers except for them.,Create a menu for a vegan Caribbean restaurant.,Create a menu for a vegan Indian restaurant.,Write a poem about the beauty of a sunrise.,Design a logo for a new wellness retreat.,Write a short story about a person who wakes up in a parallel universe.,Create a menu for a vegan pizza restaurant.,Write a poem about the colors of autumn.,Design a logo for a new language learning app.,Write a short story about a person who can communicate with ghosts.,Create a menu for a vegan French restaurant.,Write a poem about the majesty of mountains.,Design a logo for a new eco-friendly product.,Write a short story about a person who discovers they have a rare medical condition that gives them special abilities.,Create a menu for a vegan Middle Eastern restaurant.,Write a poem about the wonder of the universe.,Design a logo for a new virtual reality platform.,Write a short story about a person who travels to a distant planet and discovers a new civilization.,Create a menu for a vegan Chinese restaurant.,Write a poem about the magic of spring.,Design a logo for a new e-commerce platform."
    >
</div>