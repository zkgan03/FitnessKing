<x-layout>
    <div class="bg-cover bg-center"
        style="background-image: url('{{ Vite::asset('resources/images/coach-exam-img.png')}}')">
        <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-primary-600">Exam</h2>
            </div>

            <div class="mt-10 sm:mx-auto sm:w-full">
            <label class="block text-sm font-bold leading-6 text-white">Answer this test with around 7 out of 10 questions correct in order to become our coach. Select only one answer for each question.</label>
            <form method="POST" action="{{ route('coach-exam') }}">
                @csrf
                <!-- Render the XSLT transformed content -->
                {!! $transformedContent !!}
             <!-- Submit Button -->
             <button type="submit" 
                            class="flex w-full justify-center rounded-md bg-primary-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Submit
                    </button>
                </form>
            </div>
            
        </div>
    </div>
    
</x-layout>