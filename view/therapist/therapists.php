<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Your Therapist | Thera Care </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="therapists_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#2563eb',
                            light: '#e9f0ff',
                            dark: '#1e40af'
                        },
                    },
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                        montserrat: ['Montserrat', 'sans-serif']
                    }
                }
            }
        }
    </script>
   
</head>
<?php include '../pages/navbar.php'; ?>
<body class="bg-slate-50 font-poppins text-slate-900 leading-relaxed">
    <!-- Hero Section -->
    <section class="relative py-24 md:py-32 text-center text-white overflow-hidden" style="background-image: linear-gradient(to bottom, rgba(14, 73, 145, 0.95) 0%, rgba(14, 73, 145, 0.85) 40%, rgba(14, 73, 145, 0.7) 70%, rgba(14, 73, 145, 0) 100%);">

        
        <div class="container mx-auto px-4 relative z-10">
            <span class="inline-block bg-white/20 text-white px-4 py-2 rounded-full text-sm font-medium mb-5 backdrop-blur-sm border border-white/10">
                <i class="fas fa-user-md mr-1"></i> Find Your Specialist
            </span>
            <h1 class="text-4xl md:text-5xl font-bold mb-6 text-white">Healthcare Professionals</h1>
            <p class="text-lg md:text-xl opacity-90 max-w-2xl mx-auto">Connect with our top-rated specialists who are dedicated to providing the highest quality care for your health needs.</p>
        </div>
    </section>

    <!-- Filter Section -->
    <div class="container mx-auto px-4 relative -mt-20 z-20 max-w-4xl">
        <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 flex flex-col items-center gap-6 border border-blue-600/5">
            <div class="flex flex-wrap justify-center gap-3 w-full">
                <button class="px-6 py-3 bg-blue-600 text-white rounded-full text-sm font-medium transition hover:-translate-y-1 shadow-md shadow-blue-600/20">All Specialties</button>
                <button class="px-6 py-3 bg-white text-slate-800 border border-slate-200 rounded-full text-sm font-medium transition hover:border-blue-600 hover:bg-blue-50 hover:-translate-y-1">Orthopedic</button>
                <button class="px-6 py-3 bg-white text-slate-800 border border-slate-200 rounded-full text-sm font-medium transition hover:border-blue-600 hover:bg-blue-50 hover:-translate-y-1">Vestibular</button>
                <button class="px-6 py-3 bg-white text-slate-800 border border-slate-200 rounded-full text-sm font-medium transition hover:border-blue-600 hover:bg-blue-50 hover:-translate-y-1">Hand</button>
                <button class="px-6 py-3 bg-white text-slate-800 border border-slate-200 rounded-full text-sm font-medium transition hover:border-blue-600 hover:bg-blue-50 hover:-translate-y-1">Sports</button>
            </div>
            <div class="flex flex-wrap justify-center gap-3 w-full">
                <button class="px-6 py-3 bg-white text-slate-800 border border-slate-200 rounded-full text-sm font-medium transition hover:border-blue-600 hover:bg-blue-50 hover:-translate-y-1">Neurological</button>
                <button class="px-6 py-3 bg-white text-slate-800 border border-slate-200 rounded-full text-sm font-medium transition hover:border-blue-600 hover:bg-blue-50 hover:-translate-y-1">Geriatric</button>
                <button class="px-6 py-3 bg-white text-slate-800 border border-slate-200 rounded-full text-sm font-medium transition hover:border-blue-600 hover:bg-blue-50 hover:-translate-y-1">Cardiopulmonary</button>
                <button class="px-6 py-3 bg-white text-slate-800 border border-slate-200 rounded-full text-sm font-medium transition hover:border-blue-600 hover:bg-blue-50 hover:-translate-y-1">Oncologic</button>
            </div>
            <div class="flex items-center gap-4 pt-4 border-t border-slate-200 w-full max-w-md justify-center">
                <span class="font-medium">Sort by:</span>
                <select class="px-6 py-3 bg-white border border-slate-200 rounded-full text-sm font-medium w-48 appearance-none bg-no-repeat bg-right-4 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-600" style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2216%22 height=%2216%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%2364748b%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22%3E%3Cpath d=%22M6 9l6 6 6-6%22/%3E%3C/svg%3E'); background-position: right 15px center;">
                    <option value="relevance">Relevance</option>
                    <option value="rating">Rating</option>
                    <option value="name-asc">Name A-Z</option>
                    <option value="name-desc">Name Z-A</option>
                </select>
            </div>
        </div>
    </div>

    <?php include 'therapists_cards.php'; ?>

    <!-- Scroll to top button -->
    <div class="fixed bottom-5 right-5 w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center cursor-pointer shadow-lg shadow-blue-600/30 opacity-0 invisible transition-all duration-300 hover:bg-blue-700 hover:-translate-y-1 z-50" id="scrollToTop">
        <i class="fas fa-arrow-up"></i>
    </div>

    <script src = "therapists_script.js"></script>
    
   <?php include 'therapists_modals.php'; ?>
</script>
</body>
</html>