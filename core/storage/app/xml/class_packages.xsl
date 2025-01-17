<?xml version="1.0" encoding="UTF-8"?>

<!-- Author : Gan Zhi Ken -->


<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" />

    <xsl:param name="base_url" select="'none'"/>
    <xsl:param name="current_page" select="1"/>
    <xsl:param name="items_per_page" select="6"/>
    <xsl:param name="query" select="''"/>
    <xsl:param name="min_price" select="0"/>
    <xsl:param name="max_price" select="9999"/>
    <xsl:param name="sort_price" select="0"/>
    <xsl:param name="sort" select="'latest'"/>
    <xsl:param name="current_date" />

    <!-- Root template -->
    <xsl:template match="/">
        <!-- Container -->
        <div class="mx-32 my-10">

            <div class="flex justify-between items-center flex-col 2xl:flex-row gap-10">

                <!-- 3 Sorting button stick together -->
                <div class="mt-2 ">
                    <!-- sort by Latest-->
                    <xsl:choose>
                        <xsl:when test="$sort = 'latest'">
                            <span class="relative inline-flex items-center border border-gray-300 px-2 py-2 text-sm font-medium leading-5 ring-white bg-darkSurface-300 cursor-default rounded-l-md">
                                Latest
                            </span>
                        </xsl:when>
                        <xsl:otherwise>
                            <a href="?sort=latest&amp;q={$query}&amp;min_price={$min_price}&amp;max_price={$max_price}" class="relative inline-flex items-center border border-gray-300 px-2 rounded-l-md py-2 text-sm font-medium leading-5 ring-white transition duration-150 ease-in-out focus:z-10 focus:ring-1 active:bg-primary-500 bg-darkSurface-500 hover:bg-darkSurface-400">
                                Latest
                            </a>
                        </xsl:otherwise>
                    </xsl:choose>

                    <!-- sort by Popularity -->
                    <xsl:choose>
                        <xsl:when test="$sort = 'popularity'">
                            <span class="relative inline-flex items-center border border-gray-300 px-2 py-2 text-sm font-medium leading-5 bg-darkSurface-300 cursor-default">
                                Popularity
                            </span>
                        </xsl:when>
                        <xsl:otherwise>
                            <a href="?sort=popularity&amp;q={$query}&amp;min_price={$min_price}&amp;max_price={$max_price}" class="relative inline-flex items-center border border-gray-300 px-2 py-2 text-sm font-medium leading-5 ring-white transition duration-150 ease-in-out focus:z-10 focus:ring-1 active:bg-primary-500 bg-darkSurface-500 hover:bg-darkSurface-400">
                                Popularity
                            </a>
                        </xsl:otherwise>
                    </xsl:choose>

                    <!-- sort by Lowest Price -->
                    <xsl:choose>
                        <xsl:when test="$sort = 'lowest_price'">
                            <span class="relative inline-flex items-center border border-gray-300 px-2 py-2 text-sm font-medium leading-5 bg-darkSurface-300 cursor-default">
                                Lowest Price
                            </span>
                        </xsl:when>
                        <xsl:otherwise>
                            <a href="?sort=lowest_price&amp;q={$query}&amp;min_price={$min_price}&amp;max_price={$max_price}" class="relative inline-flex items-center border border-gray-300 px-2 py-2 text-sm font-medium leading-5 ring-white transition duration-150 ease-in-out focus:z-10 focus:ring-1 active:bg-primary-500 bg-darkSurface-500 hover:bg-darkSurface-400">
                                Lowest Price
                            </a>
                        </xsl:otherwise>
                    </xsl:choose>

                    <!-- sort by Highest Price -->
                    <xsl:choose>
                        <xsl:when test="$sort = 'highest_price'">
                            <span class="relative inline-flex items-center border border-gray-300 px-2 py-2 text-sm font-medium leading-5 bg-darkSurface-300 cursor-default rounded-r-md">
                                Highest Price
                            </span>
                        </xsl:when>
                        <xsl:otherwise>
                            <a href="?sort=highest_price&amp;q={$query}&amp;min_price={$min_price}&amp;max_price={$max_price}" class="relative inline-flex items-center border border-gray-300 px-2 py-2 text-sm font-medium leading-5 ring-white transition duration-150 ease-in-out focus:z-10 focus:ring-1 active:bg-primary-500 bg-darkSurface-500 hover:bg-darkSurface-400 rounded-r-md">
                                Highest Price
                            </a>
                        </xsl:otherwise>
                    </xsl:choose>
                </div>

                <!-- Filter and Searching -->
                <div class="flex items-center gap-10 flex-col xl:flex-row">
                    <!-- Search Bar -->
                    <div class="ml-auto relative flex h-8 justify-center">
                        <!-- Search Bar -->
                        <input id="searchInput" type="text" autocomplete="off" name="q" class="w-96 rounded-lg bg-darkSurface-500 px-5 pr-16 placeholder-opacity-50 focus:outline-none focus:ring-1 focus:ring-primary-600" placeholder="Search" />
                        <!-- Search Button -->
                        <a id="searchButton" href="javascript:void(0);" class="disabled inline-flex absolute bottom-0 right-0 top-0 w-16 rounded-lg px-5 transition duration-150 hover:text-primary-600 disabled:hover:text-[#EEEEEE]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </a>
                    </div>

                    <!-- Filters -->
                    <div class="range-slider flex flex-col rounded-lg bg-darkSurface-500 px-5 py-3 ring-primary-600 focus-within:ring-1">

                        <span class="text-sm font-medium">Price (RM)</span>

                        <div class="relative mt-2 h-2 rounded-full bg-[#DDDDDD]">
                            <div class="custom-slider absolute h-full rounded-full bg-primary-600" style="left: 0%; right: 0%;"></div>
                            <input type="range" name="min_price" step="10" value="0" min="0" max="1000" class="from-slider [&amp;::-moz-range-thumb]:size-5 [&amp;::-webkit-slider-thumb]:size-5 pointer-events-none absolute left-0 top-0 z-10 h-2 w-full appearance-none bg-transparent ring-[#F6A2BC] focus:z-20 [&amp;::-moz-range-thumb]:pointer-events-auto [&amp;::-moz-range-thumb]:cursor-pointer [&amp;::-moz-range-thumb]:appearance-none [&amp;::-moz-range-thumb]:rounded-full [&amp;::-moz-range-thumb]:bg-[#E21655] [&amp;::-moz-range-thumb]:ring-offset-1 [&amp;::-moz-range-thumb]:focus:ring-1 [&amp;::-moz-range-thumb]:focus:ring-offset-white [&amp;::-webkit-slider-thumb]:pointer-events-auto [&amp;::-webkit-slider-thumb]:cursor-pointer [&amp;::-webkit-slider-thumb]:appearance-none [&amp;::-webkit-slider-thumb]:rounded-full [&amp;::-webkit-slider-thumb]:bg-[#E21655] [&amp;::-webkit-slider-thumb]:ring-offset-1 [&amp;::-webkit-slider-thumb]:focus:ring-1 [&amp;::-webkit-slider-thumb]:focus:ring-offset-white" />
                            <input type="range" name="max_price" step="10" value="1000" min="0" max="1000" class="to-slider [&amp;::-moz-range-thumb]:size-5 [&amp;::-webkit-slider-thumb]:size-5 pointer-events-none absolute left-0 top-0 z-10 h-2 w-full appearance-none bg-transparent ring-[#F6A2BC] focus:z-20 [&amp;::-moz-range-thumb]:pointer-events-auto [&amp;::-moz-range-thumb]:cursor-pointer [&amp;::-moz-range-thumb]:appearance-none [&amp;::-moz-range-thumb]:rounded-full [&amp;::-moz-range-thumb]:bg-[#E21655] [&amp;::-moz-range-thumb]:ring-offset-1 [&amp;::-moz-range-thumb]:focus:ring-1 [&amp;::-moz-range-thumb]:focus:ring-offset-white [&amp;::-webkit-slider-thumb]:pointer-events-auto [&amp;::-webkit-slider-thumb]:cursor-pointer [&amp;::-webkit-slider-thumb]:appearance-none [&amp;::-webkit-slider-thumb]:rounded-full [&amp;::-webkit-slider-thumb]:bg-[#E21655] [&amp;::-webkit-slider-thumb]:ring-offset-1 [&amp;::-webkit-slider-thumb]:focus:ring-1 [&amp;::-webkit-slider-thumb]:focus:ring-offset-white" />
                        </div>

                        <div class="flex justify-between gap-2">
                            <div class="relative mt-2 flex h-8 w-52 items-center gap-2 w-full">
                                <input type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" class="from-input min-w-12 max-w-20  rounded-lg border-none bg-gray-700 text-center text-sm outline-none" value="0" min="0" max="1000" />
                                <input type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" class="to-input min-w-12 max-w-20  rounded-lg border-none bg-gray-700 text-center text-sm outline-none" value="1000" min="0" max="1000" />
                            </div>
                            <div class="text-right mt-6">
                                <a href="javascript:void(0);" class="applyButton inline-flex bg-primary-500 rounded px-2 py-1 hover:opacity-80 text-sm">Apply</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- curent date must before the start of the package -->
            <!-- start_date convert to yyyymmdd (in numbers, to do comparison) -->
            <xsl:variable name="filtered_packages" select="packages/package[
                                contains(
                                    translate(package_name, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'),
                                    translate($query, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz')
                                ) and 
                                price &gt;= $min_price and 
                                price &lt;= $max_price and 
                                $current_date &lt; (10000 * substring(start_date, 1, 4) + 100 * substring(start_date, 6, 2) + substring(start_date, 9, 2)) 
                            ]" />


            <!-- Class Cards -->
            <div class="flex-start mt-10 grid grid-cols-3 gap-[5dvw] gap-y-[5dvh] justify-around">
                <xsl:choose>
                    <xsl:when test="$sort = 'latest'">
                        <xsl:apply-templates select="$filtered_packages">
                            <xsl:sort select="package_id" data-type="number" order="descending"></xsl:sort>
                        </xsl:apply-templates>
                    </xsl:when>
                    <xsl:when test="$sort = 'popularity'">
                        <xsl:apply-templates select="$filtered_packages">
                            <xsl:sort select="enrollment_no" data-type="number" order="descending"></xsl:sort>
                        </xsl:apply-templates>
                    </xsl:when>
                    <xsl:when test="$sort = 'lowest_price'">
                        <xsl:apply-templates select="$filtered_packages">
                            <xsl:sort select="price" data-type="number" order="ascending"></xsl:sort>
                        </xsl:apply-templates>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:apply-templates select="$filtered_packages">
                            <xsl:sort select="price" data-type="number" order="descending"></xsl:sort>
                        </xsl:apply-templates>
                    </xsl:otherwise>
                </xsl:choose>
            </div>

            <!-- Pagination Controls -->
            <div class="pagination-controls mt-10 flex justify-center space-x-2">

                <!-- calc total items with the filter -->
                <xsl:variable name="total_items" select="count($filtered_packages)"/>

                <!-- total pages = total_item/item_per_page, then round up -->
                <xsl:variable name="total_pages" select="ceiling($total_items div $items_per_page)"/>

                <!-- show previous page button only when page larger than 1 -->
                <xsl:if test="$current_page &gt; 1">
                    <a href="?sort={$sort}&amp;page={$current_page - 1}&amp;q={$query}&amp;min_price={$min_price}&amp;max_price={$max_price}" class="relative inline-flex items-center rounded-l-md border border-gray-300 bg-darkSurface-500 px-2 py-2 text-sm font-medium leading-5 ring-white transition duration-150 ease-in-out hover:bg-darkSurface-400 focus:z-10 focus:ring-1 active:bg-primary-500">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </xsl:if>

                <!-- call the template to create the pagination links (number links) -->
                <xsl:call-template name="pagination-links">
                    <xsl:with-param name="total_pages" select="$total_pages"/>
                    <xsl:with-param name="current_page" select="$current_page"/>
                </xsl:call-template>

                <!-- show next button only when page less than total pages -->
                <xsl:if test="$current_page &lt; $total_pages">
                    <a href="?sort={$sort}&amp;page={$current_page + 1}&amp;q={$query}&amp;min_price={$min_price}&amp;max_price={$max_price}" class="relative inline-flex items-center rounded-r-md border border-gray-300 bg-darkSurface-500 px-2 py-2 text-sm font-medium leading-5 ring-white transition duration-150 ease-in-out hover:bg-darkSurface-400 focus:z-10 focus:ring-1 active:bg-primary-500">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </xsl:if>
            </div>
        </div>

        <!-- Script -->
        <script>
            // Search functionality
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('searchInput');
                const searchButton = document.getElementById('searchButton');

                // disable button when input is empty
                // enable button when input is not empty
                searchInput.addEventListener('input', function() {
                    if (searchInput.value === '') {
                        searchButton.disabled = true;
                    } else {
                        searchButton.disabled = false;
                    }
                });

                function submitSearch() {
                    const query = document.getElementById('searchInput').value;
                    const url = new URL(window.location.href);
                    url.searchParams.set('q', query);
                    url.searchParams.delete('page');
                    window.location.href = url.toString();
                }

                searchButton.addEventListener('click', function() {
                    submitSearch();
                });

                searchInput.addEventListener('keydown', function(event) {
                    if (event.key === 'Enter') {
                        submitSearch();
                    }
                });

            });


            // double range slider
            document.addEventListener('DOMContentLoaded', function() {
                const sliders = document.querySelectorAll('.range-slider');
                sliders.forEach(slider =&gt; {
                    // Sliders
                    const fromSlider = slider.querySelector('.from-slider');
                    const toSlider = slider.querySelector('.to-slider');

                    // Inputs fields for the sliders
                    const fromInput = slider.querySelector('.from-input');
                    const toInput = slider.querySelector('.to-input');

                    // Custom slider for displaying the range
                    const customSlider = slider.querySelector('.custom-slider');
                    const step = parseValue(fromSlider.step);
                    const range = toSlider.max - toSlider.min;

                    const filterButton = slider.querySelector('.applyButton');

                    function parseValue(value) {
                        return parseFloat(value);
                    }

                    function formatValue(value) {
                        return parseFloat(value).toFixed(2);
                    }

                    function updateByFromSlider() {
                        let from = parseValue(fromSlider.value);
                        let to = parseValue(toSlider.value);

                        from *= 100;
                        to *= 100;

                        if (from &gt;= to) {
                            from /= 100;
                            to /= 100;
                            fromSlider.value = to - step;
                        }
                        renderInputs();
                    }

                    function updateByToSlider() {
                        let from = parseValue(fromSlider.value);
                        let to = parseValue(toSlider.value);

                        from *= 100;
                        to *= 100;

                        if (from &gt;= to) {
                            from /= 100;
                            to /= 100;

                            toSlider.value = +from + +step;
                        }
                        renderInputs();
                    }

                    function updateByFromInput() {
                        let from = parseValue(fromInput.value);
                        let to = parseValue(toInput.value);
                        if (from &gt;= to) {
                            fromInput.value = formatValue(to - step);
                        }
                        renderSlider();
                    }

                    function updateByToInput() {
                        let from = parseValue(fromInput.value);
                        let to = parseValue(toInput.value);
                        if (from &gt;= to) {
                            toInput.value = formatValue(from + step);
                        }
                        renderSlider();
                    }


                    function renderSlider() {
                        fromSlider.value = parseValue(fromInput.value);
                        toSlider.value = parseValue(toInput.value);

                        customSlider.style.left = ((fromSlider.value - toSlider.min) / range * 100) + '%';
                        customSlider.style.right = 100 - ((toSlider.value - toSlider.min) / range * 100) +
                            '%';
                    }

                    function renderInputs() {
                        fromInput.value = formatValue(fromSlider.value);
                        toInput.value = formatValue(toSlider.value);

                        customSlider.style.left = ((fromSlider.value - toSlider.min) / range * 100) + '%';
                        customSlider.style.right = 100 - ((toSlider.value - toSlider.min) / range * 100) +
                            '%';
                    }

                    function applyFilters(){
                        const minPrice = fromInput.value;
                        const maxPrice = toInput.value;
                        const url = new URL(window.location.href);
                        url.searchParams.set('min_price', minPrice);
                        url.searchParams.set('max_price', maxPrice);
                        url.searchParams.delete('page');
                        window.location.href = url.toString();
                    }
                    filterButton.addEventListener('click', function() {
                        applyFilters();
                    });


                    fromSlider.addEventListener('input', updateByFromSlider);
                    toSlider.addEventListener('input', updateByToSlider);
                    fromInput.addEventListener('focusout', updateByFromInput);
                    toInput.addEventListener('focusout', updateByToInput);

                    // init values from query string
                    const currentMaxPrice = <xsl:value-of select="$max_price" />
                    const currentMinPrice = <xsl:value-of select="$min_price" />


                    fromSlider.value = currentMinPrice;
                    toSlider.value = currentMaxPrice;
                    fromInput.value = currentMinPrice;
                    toInput.value = currentMaxPrice;

                    renderSlider();
                    renderInputs();
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('searchInput');

                const query = `<xsl:value-of select='$query' />
`;

                if (query.trim() !== '') {
                    searchInput.value = query;
                }
            });
</script>
</xsl:template>

<!-- Template for a package card (using apply template will loop through all the item) -->
<xsl:template name="card-package" match="package">

<xsl:variable name="start" select="($current_page - 1) * $items_per_page + 1"/>
<xsl:variable name="end" select="$current_page * $items_per_page"/>

<xsl:if test="position() &gt;= $start and position() &lt;= $end">
    <div class="rounded-xl bg-darkSurface-500">
        <div>
            <img class="size-full rounded-t-xl object-cover object-center" src="{concat($base_url, package_image)}" alt=""/>
        </div>

        <div class="mt-3 flex flex-col gap-3 px-7 pb-4">
            <!-- Package Name -->
            <div class="font-bold text-xl text-ellipsis overflow-hidden whitespace-nowrap">
                <xsl:value-of select="package_name"/>
            </div>

            <!-- package info -->
            <div class="grid grid-cols-[auto,1fr] gap-x-2 text-sm">
                <div>Start Date</div>
                <div>
                    :
                    <xsl:value-of select="start_date" />
                </div>

                <div>End Date</div>
                <div>
                    :
                    <xsl:value-of select="end_date" />
                </div>

            </div>

            <!-- Description -->
            <div class="leading-5 text-sm line-clamp-4">
                <xsl:value-of select="description"/>
            </div>

            <div class="flex flex-row items-end justify-between">
                <!-- Price -->
                <div class="font-bold text-[1.5dvw] leading-tight">
                    RM <xsl:value-of select="format-number(price, '#,##0.00')"/>
                </div>

                <!-- Slot Available -->
                <div class="text-yellow-400 font-bold text-[1dvw] leading-1">
                    Slots Available : 
                    <xsl:value-of select="max_capacity - enrollment_no"/>
                </div>
            </div>
            <!-- Learn more button -->
            <div class="">
                <a href="{concat($href, '/', package_id)}" class="btn btn-primary">
                    <button class="rounded-md text-sm inline-flex px-4 py-2 justify-center 
                                    rounded-md font-semibold text-white uppercase tracking-widest bg-primary-600 
                                    hover:bg-opacity-80 focus:outline-none focus:ring-2 focus:ring-white transition ease-in-out duration-150">
                                Learn More
                    </button>
                </a>
            </div>

        </div>
    </div>
</xsl:if>
</xsl:template>

<!-- Template for all pagination links -->
<xsl:template name="pagination-links">
<xsl:param name="total_pages"/>
<xsl:param name="current_page"/>
<xsl:variable name="page" select="1"/>

<!-- call the template to create the numbering link -->
<!-- This template will call recursively until reach the max page -->
<xsl:call-template name="pagination-link">
    <xsl:with-param name="page" select="$page"/>
    <xsl:with-param name="total_pages" select="$total_pages"/>
    <xsl:with-param name="current_page" select="$current_page"/>
</xsl:call-template>
</xsl:template>

<!-- Template for a single pagination link -->
<xsl:template name="pagination-link">
<xsl:param name="page"/>
<xsl:param name="total_pages"/>
<xsl:param name="current_page"/>

<xsl:choose >
    <xsl:when test="$current_page = $page">
        <span class="relative -ml-px inline-flex cursor-default items-center border border-gray-300 bg-darkSurface-300 px-4 py-2 text-sm font-medium leading-5 dark:border-gray-600 dark:bg-gray-800">
            <xsl:value-of select="$page"/>
        </span>
    </xsl:when>
    <xsl:otherwise>
        <!-- add query string for the particular page -->
        <a href="?sort={$sort}&amp;page={$page}&amp;q={$query}&amp;min_price={$min_price}&amp;max_price={$max_price}" class="relative -ml-px inline-flex items-center border border-gray-300 bg-darkSurface-500 px-4 py-2 text-sm font-medium leading-5 ring-white transition duration-150 ease-in-out hover:bg-darkSurface-400 focus:z-10 focus:ring-1 active:bg-primary-500">
            <xsl:value-of select="$page"/>
        </a>
    </xsl:otherwise>
</xsl:choose>


<!-- if the page is less than the total pages, call the template again -->
<xsl:if test="$page &lt; $total_pages">
    <xsl:variable name="next_page" select="$page + 1"/>
    <xsl:call-template name="pagination-link">
        <xsl:with-param name="page" select="$next_page"/>
        <xsl:with-param name="total_pages" select="$total_pages"/>
        <xsl:with-param name="current_page" select="$current_page"/>
    </xsl:call-template>
</xsl:if>
</xsl:template>

</xsl:stylesheet>
