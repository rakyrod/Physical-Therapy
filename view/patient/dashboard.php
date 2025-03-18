<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thera Care</title>
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
</head>

<body class="bg-white">

    <!-- ================= HEADER SECTION ================= -->
    <div class="relative pt-48 pb-12 bg-black xl:pt-60 sm:pb-16 lg:pb-32 xl:pb-48 2xl:pb-56">

        <!-- Include the Navbar -->
        <?php include '../pages/navbar.php'; ?>


        <!-- Background Image -->
        <div class="absolute inset-0">
            <img class="object-cover w-full h-full" src="../images/doctor.png" alt="Doctor Appointment" />
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
                <a href="../authentication/login.php" title="Book Now" 
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

    
 <!-- Approach -->
 <div style="background-image: linear-gradient(to bottom, rgba(5,6,32,1), rgba(100,150,255,1));">






      <!-- Approach -->
      <div class="max-w-5xl px-4 xl:px-0 py-10 lg:pt-20  mx-auto">
        <!-- Title -->
        <div class="max-w-3xl mb-10 lg:mb-14">
          <h2 class="text-white font-semibold text-2xl md:text-4xl md:leading-tight">Our approach</h2>
          <p class="mt-1 text-neutral-400">This profound insight guides our comprehensive strategy — from meticulous research and strategic planning to the seamless execution of brand development and website or product deployment.</p>
        </div>
        <!-- End Title -->

        <!-- Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 lg:items-center">
          <div class="aspect-w-16 aspect-h-9 lg:aspect-none">
            <img class="w-full object-cover rounded-xl" src="https://images.unsplash.com/photo-1587614203976-365c74645e83?q=80&w=480&h=600&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Image Description">
          </div>
          <!-- End Col -->

          <!-- Timeline -->
          <div>
            <!-- Heading -->
            <div class="mb-4">
              <h3 class="text-xs font-medium uppercase text-[#fff]">
                Steps
              </h3>
            </div>
            <!-- End Heading -->

            <!-- Item -->
            <div class="flex gap-x-5 ms-1">
              <!-- Icon -->
              <div class="relative last:after:hidden after:absolute after:top-8 after:bottom-0 after:start-4 after:w-px after:-translate-x-[0.5px] after:bg-white text-800">
                <div class="relative z-10 size-8 flex justify-center items-center">
                  <span class="flex flex-shrink-0 justify-center items-center size-8 border border-white text-800 text-[#fff] font-semibold text-xs uppercase rounded-full">
                    1
                  </span>
                </div>
              </div>
              <!-- End Icon -->

              <!-- Right Content -->
              <div class="grow pt-0.5 pb-8 sm:pb-12">
                <p class="text-sm lg:text-base text-neutral-400">
                  <span class="text-white">Market Research and Analysis:</span>
                  Identify your target audience and understand their needs, preferences, and behaviors.
                </p>
              </div>
              <!-- End Right Content -->
            </div>
            <!-- End Item -->

            <!-- Item -->
            <div class="flex gap-x-5 ms-1">
              <!-- Icon -->
              <div class="relative last:after:hidden after:absolute after:top-8 after:bottom-0 after:start-4 after:w-px after:-translate-x-[0.5px] after:bg-white text-800">
                <div class="relative z-10 size-8 flex justify-center items-center">
                  <span class="flex flex-shrink-0 justify-center items-center size-8 border border-white text-800 text-[#fff] font-semibold text-xs uppercase rounded-full">
                    2
                  </span>
                </div>
              </div>
              <!-- End Icon -->

              <!-- Right Content -->
              <div class="grow pt-0.5 pb-8 sm:pb-12">
                <p class="text-sm lg:text-base text-neutral-400">
                  <span class="text-white">Product Development and Testing:</span>
                  Develop digital products or services that address the needs and preferences of your target audience.
                </p>
              </div>
              <!-- End Right Content -->
            </div>
            <!-- End Item -->

            <!-- Item -->
            <div class="flex gap-x-5 ms-1">
              <!-- Icon -->
              <div class="relative last:after:hidden after:absolute after:top-8 after:bottom-0 after:start-4 after:w-px after:-translate-x-[0.5px] after:bg-white text-800">
                <div class="relative z-10 size-8 flex justify-center items-center">
                  <span class="flex flex-shrink-0 justify-center items-center size-8 border border-white text-800 text-[#fff] font-semibold text-xs uppercase rounded-full">
                    3
                  </span>
                </div>
              </div>
              <!-- End Icon -->

              <!-- Right Content -->
              <div class="grow pt-0.5 pb-8 sm:pb-12">
                <p class="text-sm md:text-base text-neutral-400">
                  <span class="text-white">Marketing and Promotion:</span>
                  Develop a comprehensive marketing strategy to promote your digital products or services.
                </p>
              </div>
              <!-- End Right Content -->
            </div>
            <!-- End Item -->

            <!-- Item -->
            <div class="flex gap-x-5 ms-1">
              <!-- Icon -->
              <div class="relative last:after:hidden after:absolute after:top-8 after:bottom-0 after:start-4 after:w-px after:-translate-x-[0.5px] after:bg-white text-800">
                <div class="relative z-10 size-8 flex justify-center items-center">
                  <span class="flex flex-shrink-0 justify-center items-center size-8 border border-white text-800 text-[#fff] font-semibold text-xs uppercase rounded-full">
                    4
                  </span>
                </div>
              </div>
              <!-- End Icon -->

              <!-- Right Content -->
              <div class="grow pt-0.5 pb-8 sm:pb-12">
                <p class="text-sm md:text-base text-neutral-400">
                  <span class="text-white">Launch and Optimization:</span>
                  Launch your digital products or services to the market, closely monitoring their performance and user feedback.
                </p>
              </div>
              <!-- End Right Content -->
            </div>
            <!-- End Item -->

            <a class="group inline-flex items-center gap-x-2 py-2 px-3 bg-[#0C1030] font-medium text-sm text-white text-800 rounded-full focus:outline-none" href="#">
              <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                <path class="opacity-0 group-hover:opacity-100 group-focus:opacity-100 group-hover:delay-100 transition" d="M14.05 2a9 9 0 0 1 8 7.94"></path>
                <path class="opacity-0 group-hover:opacity-100 group-focus:opacity-100 transition" d="M14.05 6A5 5 0 0 1 18 10"></path>
              </svg>
              Schedule a call
            </a>
          </div>
          </div>
<!-- End Grid -->

</div>
</div>
<!-- End Approach -->
 <!-- Contact -->
  
 <div style="background-image: linear-gradient(to bottom, rgba(100,150,255,1), rgba(4,4,29,1));">





  <div class="max-w-5xl px-4 xl:px-0 py-10 lg:py-20 mx-auto">
        <!-- Title -->
        <div class="max-w-3xl mb-10 lg:mb-14">
          <h2 class="text-white font-semibold text-2xl md:text-4xl md:leading-tight">Contact us</h2>
          <p class="mt-1 text-white text-400">Whatever your goal - we will get you there.</p>
        </div>
        <!-- End Title -->

   <!-- Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 lg:gap-x-16">
  <div class="md:order-2 border-b border-neutral-800 pb-10 mb-10 md:border-b-0 md:pb-0 md:mb-0">
    <form>
      <div class="space-y-4">
        <!-- Input -->
        <div class="relative">
          <input type="text" id="hs-tac-input-name" class="peer p-4 block w-full bg-white border-transparent rounded-lg text-sm text-neutral-800 placeholder:text-transparent focus:outline-none focus:ring-0 focus:border-transparent disabled:opacity-50 disabled:pointer-events-none
          focus:pt-6
          focus:pb-2
          [&:not(:placeholder-shown)]:pt-6
          [&:not(:placeholder-shown)]:pb-2
          autofill:pt-6
          autofill:pb-2" placeholder="Name">
          <label for="hs-tac-input-name" class="absolute top-0 start-0 p-4 h-full text-neutral-600 text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent peer-disabled:opacity-50 peer-disabled:pointer-events-none
            peer-focus:text-xs
            peer-focus:-translate-y-1.5
            peer-focus:text-neutral-600
            peer-[:not(:placeholder-shown)]:text-xs
            peer-[:not(:placeholder-shown)]:-translate-y-1.5
            peer-[:not(:placeholder-shown)]:text-neutral-600">Name</label>
        </div>
        <!-- End Input -->

        <!-- Input -->
        <div class="relative">
          <input type="email" id="hs-tac-input-email" class="peer p-4 block w-full bg-white border-transparent rounded-lg text-sm text-neutral-800 placeholder:text-transparent focus:outline-none focus:ring-0 focus:border-transparent disabled:opacity-50 disabled:pointer-events-none
          focus:pt-6
          focus:pb-2
          [&:not(:placeholder-shown)]:pt-6
          [&:not(:placeholder-shown)]:pb-2
          autofill:pt-6
          autofill:pb-2" placeholder="Email">
          <label for="hs-tac-input-email" class="absolute top-0 start-0 p-4 h-full text-neutral-600 text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent peer-disabled:opacity-50 peer-disabled:pointer-events-none
            peer-focus:text-xs
            peer-focus:-translate-y-1.5
            peer-focus:text-neutral-600
            peer-[:not(:placeholder-shown)]:text-xs
            peer-[:not(:placeholder-shown)]:-translate-y-1.5
            peer-[:not(:placeholder-shown)]:text-neutral-600">Email</label>
        </div>
        <!-- End Input -->

        <!-- Input -->
        <div class="relative">
          <input type="text" id="hs-tac-input-company" class="peer p-4 block w-full bg-white border-transparent rounded-lg text-sm text-neutral-800 placeholder:text-transparent focus:outline-none focus:ring-0 focus:border-transparent disabled:opacity-50 disabled:pointer-events-none
          focus:pt-6
          focus:pb-2
          [&:not(:placeholder-shown)]:pt-6
          [&:not(:placeholder-shown)]:pb-2
          autofill:pt-6
          autofill:pb-2" placeholder="Company">
          <label for="hs-tac-input-company" class="absolute top-0 start-0 p-4 h-full text-neutral-600 text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent peer-disabled:opacity-50 peer-disabled:pointer-events-none
            peer-focus:text-xs
            peer-focus:-translate-y-1.5
            peer-focus:text-neutral-600
            peer-[:not(:placeholder-shown)]:text-xs
            peer-[:not(:placeholder-shown)]:-translate-y-1.5
            peer-[:not(:placeholder-shown)]:text-neutral-600">Company</label>
        </div>
        <!-- End Input -->

        <!-- Input -->
        <div class="relative">
          <input type="text" id="hs-tac-input-phone" class="peer p-4 block w-full bg-white border-transparent rounded-lg text-sm text-neutral-800 placeholder:text-transparent focus:outline-none focus:ring-0 focus:border-transparent disabled:opacity-50 disabled:pointer-events-none
          focus:pt-6
          focus:pb-2
          [&:not(:placeholder-shown)]:pt-6
          [&:not(:placeholder-shown)]:pb-2
          autofill:pt-6
          autofill:pb-2" placeholder="Phone">
          <label for="hs-tac-input-phone" class="absolute top-0 start-0 p-4 h-full text-neutral-600 text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent peer-disabled:opacity-50 peer-disabled:pointer-events-none
            peer-focus:text-xs
            peer-focus:-translate-y-1.5
            peer-focus:text-neutral-600
            peer-[:not(:placeholder-shown)]:text-xs
            peer-[:not(:placeholder-shown)]:-translate-y-1.5
            peer-[:not(:placeholder-shown)]:text-neutral-600">Phone</label>
        </div>
        <!-- End Input -->

        <!-- Textarea -->
        <div class="relative">
          <textarea id="hs-tac-message" class="peer p-4 block w-full bg-white border-transparent rounded-lg text-sm text-neutral-800 placeholder:text-transparent focus:outline-none focus:ring-0 focus:border-transparent disabled:opacity-50 disabled:pointer-events-none
          focus:pt-6
          focus:pb-2
          [&:not(:placeholder-shown)]:pt-6
          [&:not(:placeholder-shown)]:pb-2
          autofill:pt-6
          autofill:pb-2" placeholder="This is a textarea placeholder"></textarea>
          <label for="hs-tac-message" class="absolute top-0 start-0 p-4 h-full text-neutral-600 text-sm truncate pointer-events-none transition ease-in-out duration-100 border border-transparent peer-disabled:opacity-50 peer-disabled:pointer-events-none
            peer-focus:text-xs
            peer-focus:-translate-y-1.5
            peer-focus:text-neutral-600
            peer-[:not(:placeholder-shown)]:text-xs
            peer-[:not(:placeholder-shown)]:-translate-y-1.5
            peer-[:not(:placeholder-shown)]:text-neutral-600">Tell us about your project</label>
        </div>
        <!-- End Textarea -->
      </div>

      <div class="mt-2">
        <p class="text-xs text-white text-500">
          All fields are required
        </p>

        <p class="mt-5">
          <a class="group inline-flex items-center gap-x-2 py-2 px-3 bg-[#547EDA] font-medium text-sm text-white text-800 rounded-full focus:outline-none" href="#">
            Submit
            <svg class="flex-shrink-0 size-4 transition group-hover:translate-x-0.5 group-hover:translate-x-0 group-focus:translate-x-0.5 group-focus:translate-x-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M5 12h14" />
              <path d="m12 5 7 7-7 7" />
            </svg>
          </a>
        </p>
      </div>
    </form>
  </div>
  <!-- End Col -->

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
          300 Bath Street, Tay House<br>
          Glasgow G2 4JR, United Kingdom
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
          hello@example.so
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
        <p class="mt-1 text-white text-400">We're thrilled to announce that we're expanding our team and looking for talented individuals like you to join us.</p>
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

    

   

   <script src="dashboard_script.js"></script>

</body>
</html>
