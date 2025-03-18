<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thera Care</title>
    <!-- Preconnect for better font loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script> <!-- Tailwind CSS -->
    <script>
        tailwind.config = {
            theme: {
                fontFamily: {
                    'sans': ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                    'inter': ['Inter', 'sans-serif']
                }
            }
        }
    </script>
    <style>
        body, h1, h2, h3, h4, h5, h6, p, span, a, button, input, textarea, select, label {
            font-family: 'Inter', sans-serif !important;
        }
        
        /* The "stronger you" text has a different style */
        .font-serif {
            font-family: serif !important; /* Keep this one as serif */
        }
    </style>
</head>
<body class="bg-white">

    <!-- ================= HEADER SECTION ================= -->
    <div class="relative pt-48 pb-12 bg-black xl:pt-60 sm:pb-16 lg:pb-32 xl:pb-48 2xl:pb-56">

        <!-- Include the Navbar -->
        <?php include 'pages/navbar.php'; ?>


        <!-- Background Image -->
        <div class="absolute inset-0">
        <img class="object-cover w-full h-full" src="../images/doctor.png" alt="Doctor Appointment" />
             alt="Doctor Appointment" />
        </div>

        <!-- ================ HERO SECTION ================ -->
        <div class="relative">
            <div class="px-6 mx-auto sm:px-8 lg:px-12 max-w-7xl">
                <div class="w-full lg:w-2/3 xl:w-1/2">
                    <h1 class="font-inter text-lg md:text-xl lg:text-2xl font-medium tracking-wide text-white border-l-4 border-blue-400 pl-3 py-1 mb-3 shadow-sm">
    Restore the joy of movement.
</h1>
                    <p class="mt-6 tracking-tighter text-white">
                        <span class="font-sans font-normal text-7xl">The road to the</span><br />
                        <span class="font-serif italic font-normal text-8xl">stronger you</span>
                    </p>
                    <p class="mt-12 font-sans text-base font-normal leading-7 text-white text-opacity-70">
    At Thera Care, we are proud to serve the Davao community with personalized and 
    compassionate physical therapy services. Our team is dedicated to helping you
    regain strength, mobility, and confidence in every step of your journey.
</p>
                    <!-- Pricing with badge styling -->
            <div class="mt-10 inline-flex items-center px-4 py-1.5 bg-blue-900 bg-opacity-40 rounded-full border border-blue-400 border-opacity-30">
                <p class="font-inter text-lg md:text-xl font-medium text-white">Starting at <span class="font-semibold text-blue-200">₱600</span>/session</p>
            </div>


                 <!-- Enhanced CTA BUTTONS -->
            <div class="flex flex-wrap items-center mt-8 gap-4 sm:gap-5">
                <!-- Book Now Button - Enhanced -->
                <!-- Book Now Button - Fixed Path -->
<a href="authentication/login.php" title="Book Now" 
   class="inline-flex items-center justify-center px-6 py-3 font-inter text-base font-semibold transition-all duration-300 border-2 border-transparent rounded-full sm:text-lg text-blue-900 bg-white hover:bg-blue-50 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-800 shadow-md" 
   role="button">
    Book Now
</a>


                        </a>

                          <!-- Watch Sessions Button - Enhanced -->
               <!-- Watch Sessions Button - Enhanced -->
<a href="https://www.youtube.com/watch?v=s1jm_V9W3XY" title="Watch Sessions" 
   class="inline-flex items-center justify-center px-6 py-3 font-inter text-base font-semibold transition-all duration-300 border-2 rounded-full sm:text-lg text-white border-white border-opacity-60 hover:border-opacity-100 hover:bg-white hover:bg-opacity-10 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-1 focus:ring-offset-blue-800" 
   role="button" target="_blank" rel="noopener noreferrer">
    <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M8.0416 4.9192C7.37507 4.51928 6.5271 4.99939 6.5271 5.77669L6.5271 18.2232C6.5271 19.0005 7.37507 19.4806 8.0416 19.0807L18.4137 12.8574C19.061 12.469 19.061 11.5308 18.4137 11.1424L8.0416 4.9192Z" />
    </svg>
    Watch Sessions
</a>

            </div>

                </div>
            </div>
        </div>
    </div> <!-- End of Hero Section -->

    
 <!-- Approach Section - Modern Design with Original Colors -->
<section style="background-image: linear-gradient(to bottom, rgba(5,6,32,1), rgba(100,150,255,1));" class="py-20 relative overflow-hidden">
  <!-- Background Elements -->
  <div class="absolute inset-0">
    <div class="absolute top-0 right-0 w-1/2 h-1/2 bg-white/5 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-3/4 h-1/2 bg-white/5 blur-3xl"></div>
    
    <!-- Decorative Circles -->
    <div class="absolute top-20 left-20 w-64 h-64 rounded-full border border-white/10"></div>
    <div class="absolute top-40 left-40 w-32 h-32 rounded-full border border-white/20"></div>
    <div class="absolute bottom-40 right-20 w-80 h-80 rounded-full border border-white/10"></div>
    <div class="absolute -right-20 top-1/3 w-40 h-40 rounded-full bg-white/5 blur-xl"></div>
  </div>

  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
    <!-- Header -->
    <div class="max-w-3xl mx-auto text-center mb-16">
      <span class="inline-block px-3 py-1 text-xs font-semibold bg-[#0C1030] text-white rounded-full mb-3">Our Process</span>
      <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-6 tracking-tight">Our approach</h2>
      <p class="text-neutral-400 text-lg">At Thera Care, we prioritize your well-being by offering a seamless and personalized experience for booking treatments at our clinic. Our step-by-step approach ensures that you receive the best care tailored to your needs.</p>
    </div>

    <!-- Steps Container -->
    <div class="grid gap-12 mt-16">
      <div class="flex flex-col gap-12 md:gap-0 md:flex-row">
        <!-- Step 1 -->
        <div class="relative md:w-1/2">
          <div class="md:pr-8 md:border-r border-white/20 h-full flex flex-col items-end">
            <div class="flex items-start gap-4 max-w-xs">
              <div class="flex-shrink-0 w-10 h-16 flex items-center justify-center rounded-full bg-[#0C1030] shadow-md relative">
                <span class="text-white font-bold text-2xl">1</span>
              </div>
              <div>
                <h3 class="text-xl font-bold text-white mb-2">Explore Our Services</h3>
                <p class="text-neutral-400">Browse through our wide range of treatments and therapies to find the one that suits your needs.</p>
              </div>
            </div>
          </div>
          <!-- Connector for desktop view -->
          <div class="hidden md:block absolute top-6 right-0 transform translate-x-1/2 w-4 h-4 rounded-full bg-white"></div>
        </div>

        <!-- Step 2 -->
        <div class="relative md:w-1/2">
          <div class="md:pl-8 h-full">
            <div class="flex items-start gap-4 md:justify-start max-w-xs md:ml-auto">
              <div class="flex-shrink-0 w-10 h-16 flex items-center justify-center rounded-full bg-[#0C1030] shadow-md relative">
                <span class="text-white font-bold text-2xl">2</span>
              </div>
              <div>
                <h3 class="text-xl font-bold text-white mb-2">Schedule a Consultation</h3>
                <p class="text-neutral-400">Choose a convenient date and time for your consultation through our online booking system or by calling our clinic.</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Timeline connector -->
      <div class="hidden md:flex justify-center">
        <div class="h-10 w-0.5 bg-white/50"></div>
      </div>

      <div class="flex flex-col gap-12 md:gap-0 md:flex-row">
        <!-- Step 3 -->
        <div class="relative md:w-1/2">
          <div class="md:pr-8 md:border-r border-white/20 h-full flex flex-col items-end">
            <div class="flex items-start gap-4 max-w-xs">
              <div class="flex-shrink-0 w-10 h-16 flex items-center justify-center rounded-full bg-[#0C1030] shadow-md relative">
                <span class="text-white font-bold text-2xl">3</span>
              </div>
              <div>
                <h3 class="text-xl font-bold text-white mb-2">Confirm Your Appointment</h3>
                <p class="text-neutral-400">Receive a confirmation via email or SMS with the details of your scheduled session.</p>
              </div>
            </div>
          </div>
          <!-- Connector for desktop view -->
          <div class="hidden md:block absolute top-6 right-0 transform translate-x-1/2 w-4 h-4 rounded-full bg-white"></div>
        </div>

        <!-- Step 4 -->
        <div class="relative md:w-1/2">
          <div class="md:pl-8 h-full">
            <div class="flex items-start gap-4 md:justify-start max-w-xs md:ml-auto">
              <div class="flex-shrink-0 w-14 h-14 flex items-center justify-center rounded-full bg-[#0C1030]/80 backdrop-blur-sm shadow-lg relative group">
                <span class="absolute inset-0 rounded-full border-2 border-white/30 opacity-50 group-hover:opacity-70 transition-opacity"></span>
                <span class="absolute inset-1 rounded-full border border-white/20"></span>
                <span class="relative text-white font-bold text-2xl z-10 group-hover:scale-110 transition-transform">4</span>
                <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-white/30 blur-sm"></span>
              </div>
              <div>
                <h3 class="text-xl font-bold text-white mb-2">Visit Our Clinic</h3>
                <p class="text-neutral-400">Arrive at our clinic on your appointment day, and our expert therapists will guide you through your treatment journey.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- CTA Button -->
    <div class="mt-20 text-center">
      <a href="#" class="group inline-flex items-center gap-3 bg-[#0C1030] px-6 py-3 rounded-full text-white font-medium shadow-lg hover:shadow-white/10 transition-all duration-300 hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-white/20 focus:ring-offset-2 focus:ring-offset-[#0C1030]">
        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
          <path class="opacity-0 group-hover:opacity-100 transition-opacity duration-300" d="M14.05 2a9 9 0 0 1 8 7.94"></path>
          <path class="opacity-0 group-hover:opacity-100 transition-opacity duration-300" d="M14.05 6A5 5 0 0 1 18 10"></path>
        </svg>
        Schedule a call
        <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-all duration-300 -translate-x-4 group-hover:translate-x-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M5 12h14"></path>
          <path d="m12 5 7 7-7 7"></path>
        </svg>
      </a>
    </div>
  </div>
</section>


<?php include("index_controller.php"); ?>

<!-- Contact -->
<div style="background-image: linear-gradient(to bottom, rgba(100,150,255,1), rgba(4,4,29,1));">
  <div class="max-w-5xl px-4 xl:px-0 py-10 lg:py-20 mx-auto">
        <!-- Title -->
        <div class="max-w-3xl mb-10 lg:mb-14">
          <h2 class="text-white font-semibold text-2xl md:text-4xl md:leading-tight">Contact us</h2>
          <p class="mt-1 text-white text-400">Helping You Move Better, Feel Better.</p>
        </div>
        <!-- End Title -->

   <!-- Grid -->
   <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 lg:gap-x-16">
     <div class="md:order-2 border-b border-neutral-800 pb-10 mb-10 md:border-b-0 md:pb-0 md:mb-0">
       <?php if ($formSubmitted): ?>
        
       <div class="bg-green-500/20 border border-green-500 text-white rounded-lg p-4 mb-6">
         <p>Thank you for contacting us! We'll get back to you shortly.</p>
       </div>
       <?php endif; ?>
       
       <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
         <div class="space-y-4">
           <!-- Name Input -->
           <div class="relative">
             <input type="text" name="name" id="hs-tac-input-name" value="<?php echo $name; ?>" class="peer p-4 block w-full bg-white border-transparent rounded-lg text-sm text-neutral-800 placeholder:text-transparent focus:outline-none focus:ring-0 focus:border-transparent disabled:opacity-50 disabled:pointer-events-none
             focus:pt-6
             focus:pb-2
             [&:not(:placeholder-shown)]:pt-6
             [&:not(:placeholder-shown)]:pb-2
             autofill:pt-6
             autofill:pb-2 <?php echo (!empty($nameErr)) ? 'border-red-500' : ''; ?>" placeholder="Name">
             <label for="hs-tac-input-name" class="absolute top-0 start-0 p-4 h-full text-neutral-600 text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent peer-disabled:opacity-50 peer-disabled:pointer-events-none
               peer-focus:text-xs
               peer-focus:-translate-y-1.5
               peer-focus:text-neutral-600
               peer-[:not(:placeholder-shown)]:text-xs
               peer-[:not(:placeholder-shown)]:-translate-y-1.5
               peer-[:not(:placeholder-shown)]:text-neutral-600">Name</label>
             <?php if (!empty($nameErr)): ?>
             <p class="text-red-500 text-xs mt-1"><?php echo $nameErr; ?></p>
             <?php endif; ?>
           </div>
           <!-- End Name Input -->

           <!-- Email Input -->
           <div class="relative">
             <input type="email" name="email" id="hs-tac-input-email" value="<?php echo $email; ?>" class="peer p-4 block w-full bg-white border-transparent rounded-lg text-sm text-neutral-800 placeholder:text-transparent focus:outline-none focus:ring-0 focus:border-transparent disabled:opacity-50 disabled:pointer-events-none
             focus:pt-6
             focus:pb-2
             [&:not(:placeholder-shown)]:pt-6
             [&:not(:placeholder-shown)]:pb-2
             autofill:pt-6
             autofill:pb-2 <?php echo (!empty($emailErr)) ? 'border-red-500' : ''; ?>" placeholder="Email">
             <label for="hs-tac-input-email" class="absolute top-0 start-0 p-4 h-full text-neutral-600 text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent peer-disabled:opacity-50 peer-disabled:pointer-events-none
               peer-focus:text-xs
               peer-focus:-translate-y-1.5
               peer-focus:text-neutral-600
               peer-[:not(:placeholder-shown)]:text-xs
               peer-[:not(:placeholder-shown)]:-translate-y-1.5
               peer-[:not(:placeholder-shown)]:text-neutral-600">Email</label>
             <?php if (!empty($emailErr)): ?>
             <p class="text-red-500 text-xs mt-1"><?php echo $emailErr; ?></p>
             <?php endif; ?>
           </div>
           <!-- End Email Input -->

           <!-- About Input -->
           <div class="relative">
             <input type="text" name="about" id="hs-tac-input-company" value="<?php echo $about; ?>" class="peer p-4 block w-full bg-white border-transparent rounded-lg text-sm text-neutral-800 placeholder:text-transparent focus:outline-none focus:ring-0 focus:border-transparent disabled:opacity-50 disabled:pointer-events-none
             focus:pt-6
             focus:pb-2
             [&:not(:placeholder-shown)]:pt-6
             [&:not(:placeholder-shown)]:pb-2
             autofill:pt-6
             autofill:pb-2 <?php echo (!empty($aboutErr)) ? 'border-red-500' : ''; ?>" placeholder="About">
             <label for="hs-tac-input-company" class="absolute top-0 start-0 p-4 h-full text-neutral-600 text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent peer-disabled:opacity-50 peer-disabled:pointer-events-none
               peer-focus:text-xs
               peer-focus:-translate-y-1.5
               peer-focus:text-neutral-600
               peer-[:not(:placeholder-shown)]:text-xs
               peer-[:not(:placeholder-shown)]:-translate-y-1.5
               peer-[:not(:placeholder-shown)]:text-neutral-600">About</label>
             <?php if (!empty($aboutErr)): ?>
             <p class="text-red-500 text-xs mt-1"><?php echo $aboutErr; ?></p>
             <?php endif; ?>
           </div>
           <!-- End About Input -->

           <!-- Phone Input -->
           <div class="relative">
             <input type="text" name="phone" id="hs-tac-input-phone" value="<?php echo $phone; ?>" class="peer p-4 block w-full bg-white border-transparent rounded-lg text-sm text-neutral-800 placeholder:text-transparent focus:outline-none focus:ring-0 focus:border-transparent disabled:opacity-50 disabled:pointer-events-none
             focus:pt-6
             focus:pb-2
             [&:not(:placeholder-shown)]:pt-6
             [&:not(:placeholder-shown)]:pb-2
             autofill:pt-6
             autofill:pb-2 <?php echo (!empty($phoneErr)) ? 'border-red-500' : ''; ?>" placeholder="Phone">
             <label for="hs-tac-input-phone" class="absolute top-0 start-0 p-4 h-full text-neutral-600 text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent peer-disabled:opacity-50 peer-disabled:pointer-events-none
               peer-focus:text-xs
               peer-focus:-translate-y-1.5
               peer-focus:text-neutral-600
               peer-[:not(:placeholder-shown)]:text-xs
               peer-[:not(:placeholder-shown)]:-translate-y-1.5
               peer-[:not(:placeholder-shown)]:text-neutral-600">Phone</label>
             <?php if (!empty($phoneErr)): ?>
             <p class="text-red-500 text-xs mt-1"><?php echo $phoneErr; ?></p>
             <?php endif; ?>
           </div>
           <!-- End Phone Input -->

           <!-- Textarea -->
           <div class="relative">
             <textarea name="message" id="hs-tac-message" class="peer p-4 block w-full bg-white border-transparent rounded-lg text-sm text-neutral-800 placeholder:text-transparent focus:outline-none focus:ring-0 focus:border-transparent disabled:opacity-50 disabled:pointer-events-none
             focus:pt-6
             focus:pb-2
             [&:not(:placeholder-shown)]:pt-6
             [&:not(:placeholder-shown)]:pb-2
             autofill:pt-6
             autofill:pb-2 <?php echo (!empty($messageErr)) ? 'border-red-500' : ''; ?>" placeholder="This is a textarea placeholder"><?php echo $message; ?></textarea>
             <label for="hs-tac-message" class="absolute top-0 start-0 p-4 h-full text-neutral-600 text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent peer-disabled:opacity-50 peer-disabled:pointer-events-none
               peer-focus:text-xs
               peer-focus:-translate-y-1.5
               peer-focus:text-neutral-600
               peer-[:not(:placeholder-shown)]:text-xs
               peer-[:not(:placeholder-shown)]:-translate-y-1.5
               peer-[:not(:placeholder-shown)]:text-neutral-600">Tell us about your concern</label>
             <?php if (!empty($messageErr)): ?>
             <p class="text-red-500 text-xs mt-1"><?php echo $messageErr; ?></p>
             <?php endif; ?>
           </div>
           <!-- End Textarea -->
         </div>

         <div class="mt-2">
           <p class="text-xs text-white text-500">
             All fields are required
           </p>

           <p class="mt-5">
             <button type="submit" class="group inline-flex items-center gap-x-2 py-2 px-3 bg-[#547EDA] font-medium text-sm text-white text-800 rounded-full focus:outline-none hover:bg-[#3E68C5] transition-colors duration-300">
               Submit
               <svg class="flex-shrink-0 size-4 transition group-hover:translate-x-0.5 group-hover:translate-x-0 group-focus:translate-x-0.5 group-focus:translate-x-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                 <path d="M5 12h14" />
                 <path d="m12 5 7 7-7 7" />
               </svg>
             </button>
           </p>
         </div>
       </form>
     </div>
     <!-- End Col -->
     
     <!-- Contact Information Column -->
     <div class="md:order-1">
       <div class="space-y-6">
         <h3 class="text-white text-xl font-semibold">Get in touch</h3>
         <p class="text-neutral-400">If you have any questions about our services or would like to schedule an appointment, please don't hesitate to contact us.</p>
         
         <div class="space-y-4">
           <div class="flex items-start">
             <div class="flex-shrink-0 mt-1">
               <svg class="w-5 h-5 text-[#547EDA]" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                 <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
               </svg>
             </div>
             <div class="ml-3">
               <p class="text-white font-medium">Phone</p>
               <p class="text-neutral-400">(555) 123-4567</p>
             </div>
           </div>
           
           <div class="flex items-start">
             <div class="flex-shrink-0 mt-1">
               <svg class="w-5 h-5 text-[#547EDA]" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                 <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                 <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
               </svg>
             </div>
             <div class="ml-3">
               <p class="text-white font-medium">Email</p>
               <p class="text-neutral-400">contact@theracare.com</p>
             </div>
           </div>
           
           <div class="flex items-start">
             <div class="flex-shrink-0 mt-1">
               <svg class="w-5 h-5 text-[#547EDA]" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                 <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                 <circle cx="12" cy="10" r="3"></circle>
               </svg>
             </div>
             <div class="ml-3">
               <p class="text-white font-medium">Location</p>
               <p class="text-neutral-400">123 Therapy Lane, Suite 100<br>Wellness City, WC 12345</p>
             </div>
           </div>
         </div>
         
         <div class="pt-4">
           <h4 class="text-white text-lg font-medium mb-3">Office Hours</h4>
           <ul class="space-y-2 text-neutral-400">
             <li class="flex justify-between">
               <span>Monday - Friday</span>
               <span>8:00 AM - 6:00 PM</span>
             </li>
             <li class="flex justify-between">
               <span>Saturday</span>
               <span>9:00 AM - 2:00 PM</span>
             </li>
             <li class="flex justify-between">
               <span>Sunday</span>
               <span>Closed</span>
             </li>
           </ul>
         </div>
       </div>
     </div>
     <!-- End Contact Information Column -->
   </div>
   <!-- End Grid -->
 </div>
</div>
<!-- End Contact -->

  <div class="space-y-14">
    <!-- Item -->
    <div class="flex gap-x-5">
      <svg class="flex-shrink-0 size-6 text-white text-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
        <circle cx="12" cy="10" r="3" />
      </svg>
      <div class="grow">
        <h4 class="text-white font-semibold">Our address:</h4>

        <address class="mt-1 text-white text-400 text-sm not-italic">
        123 Wellness Avenue, <br>
        Davao City, Philippines
        </address>
      </div>
    </div>
    <!-- End Item -->

    <!-- Item -->
    <div class="flex gap-x-5">
      <svg class="flex-shrink-0 size-6 text-white text-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21.2 8.4c.5.38.8.97.8 1.6v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V10a2 2 0 0 1 .8-1.6l8-6a2 2 0 0 1 2.4 0l8 6Z" />
        <path d="m22 10-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 10" />
      </svg>
      <div class="grow">
        <h4 class="text-white font-semibold">Email us:</h4>

        <a class="mt-1 text-white text-400 text-sm" href="#mailto:example@site.co" target="_blank">
          contact@theracare.com
        </a>
      </div>
    </div>
    <!-- End Item -->

    <!-- Item -->
    <div class="flex gap-x-5">
      <svg class="flex-shrink-0 size-6 text-white text-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="m3 11 18-5v12L3 14v-3z" />
        <path d="M11.6 16.8a3 3 0 1 1-5.8-1.6" />
      </svg>
      <div class="grow">
        <h4 class="text-white font-semibold">We're hiring</h4>
        <p class="mt-1 text-white text-400">We're expanding our team of skilled physical therapists. Join us in making a difference!</p>
        <p class="mt-2">
          <a class="group inline-flex items-center gap-x-2 font-medium text-sm text-[#ffffff] decoration-2 hover:underline focus:outline-none focus:underline" href="#">
            Job openings
            <svg class="flex-shrink-0 size-4 transition group-hover:translate-x-0.5 group-hover:translate-x-0 group-focus:translate-x-0.5 group-focus:translate-x-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M5 12h14" />
              <path d="m12 5 7 7-7 7" />
            </svg>
          </a>
        </p>
      </div>
    </div>
    <!-- End Item -->
  </div>
  <!-- End Col -->
</div>
<!-- End Grid -->

    

   <script src = "index_script.js"></script>


</body>
</html>