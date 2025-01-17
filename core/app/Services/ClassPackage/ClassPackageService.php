<?php

namespace App\Services\ClassPackage;

use Carbon\Carbon;
use DOMDocument;
use Illuminate\Support\Collection;
use XSLTProcessor;
use App\Models\ClassPackage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Author:  GAN ZHI KEN
 * 
 */
class ClassPackageService
{
    /**
     *  Get all class packages available in database
     * 
     * @return Collection
     */
    public function getAllPackages(): Collection
    {
        return ClassPackage::all()
            ->toBase();
    }

    /**
     * Get all class packages available in database with pagination
     * 
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedPackages(int $perPage): LengthAwarePaginator
    {
        return ClassPackage::paginate($perPage)->withQueryString();
    }


    /**
     * Get a class package with a specific id
     * 
     * @param int $id
     * @return object
     */
    public function getPackageById($package_id): object
    {
        $classPackage = ClassPackage::find($package_id);
        return (object) $classPackage->toArray();
    }

    /**
     * Get all classes that belong to a specific package
     * 
     * @param mixed $package_id
     * @return Collection
     */
    public function getClassesById($package_id): Collection
    {
        return ClassPackage::find($package_id)
            ->gymClasses()
            ->get()
            ->toBase();
    }

    /**
     * Get the number of slots available for a specific package
     * 
     * @param mixed $package_id
     * @return int
     */
    public function getSlotAvailableById($package_id): int
    {
        $numberEnrolled = ClassPackage::find($package_id)->enrollments()->count();
        $maxCapacity = ClassPackage::find($package_id)->max_capacity;
        $slots = $maxCapacity - $numberEnrolled;

        return $slots;
    }

    /**
     * Create and save an XML file containing all the class packages
     * 
     * @return void
     */
    public function createAndSaveAllPackagesAsXML(): void
    {
        $packages = $this->getAllPackages();

        // Create a new SimpleXMLElement, to create the XML from the packages
        $xml = new \SimpleXMLElement('<packages/>');

        // Loop through each package and add to the XML
        foreach ($packages as $package) {
            $packageNode = $xml->addChild('package');
            $packageNode->addChild('package_id', $package['package_id']);
            $packageNode->addChild('package_name', htmlspecialchars($package['package_name']));
            $packageNode->addChild('description', htmlspecialchars($package['description']));
            $packageNode->addChild('start_date', $package['start_date']);
            $packageNode->addChild('end_date', $package['end_date']);
            $packageNode->addChild('price', $package['price']);
            $packageNode->addChild('enrollment_no', ClassPackage::find($package['package_id'])->enrollments()->count());
            $packageNode->addChild('max_capacity', $package['max_capacity']);
            $packageNode->addChild('package_image', $package['package_image']);
        }

        // Convert the XML object to a string
        $xmlString = $xml->asXML();
        $filePath = 'xml/class_packages.xml';

        Storage::put(path: $filePath, contents: $xmlString);
    }

    /**
     * Transform the packages XML to HTML using XSLT
     * 
     * @return string
     */
    public function packagesXmlToHtml(): string
    {
        // if the XML file does not exist, create and save it
        $this->createAndSaveAllPackagesAsXML();

        $xmlPath = Storage::path('xml/class_packages.xml');
        $xslPath = Storage::path('xml/class_packages.xsl');

        // Load the XML file
        $xml = new DOMDocument;
        $xml->load($xmlPath);

        // Load the XSL file
        $xsl = new DOMDocument;
        $xsl->load($xslPath);

        // Configure the transformer
        $proc = new XSLTProcessor;
        $proc->importStyleSheet($xsl);

        // set params
        $filePrefix = url('storage\\');
        $filePrefix = str_replace('\\', '/', $filePrefix);
        $href = route('class_package.index');
        $currentDate = Carbon::now()->format('Ymd');
        dd($currentDate);
        $proc->setParameter('', 'file_prefix', $filePrefix);
        $proc->setParameter('', 'href', $href);
        $proc->setParameter('', 'current_date', Carbon::now()->format('Ymd'));

        // Transform XML to HTML and output
        $html = $proc->transformToXML($xml);

        return $html;
    }

    /**
     * Transform the packages XML to HTML using XSLT and paginate the results
     * 
     * @param int $currentPage
     * @param int $itemsPerPage
     * @param string $sort
     * @param array{query: string, min_price: float, max_price: float} $filter  [query = > "abcdefghijklmnopqrstuvwxyz", min_price => 0 , max_price => 9999]
     * 
     * @return string
     */
    public function packagesToHtmlPaginatedWithFilter(
        int $currentPage = 1,
        int $itemsPerPage = 6,
        string $sort = "latest",
        array $filter
    ): string {
        $this->createAndSaveAllPackagesAsXML();

        $query = htmlspecialchars($filter['query']) ?? '';
        $minPrice = htmlspecialchars($filter['min_price']) ?? 0;
        $maxPrice = htmlspecialchars($filter['max_price']) ?? 9999;

        // dd($query, $minPrice, $maxPrice);
        // dd($query, $minPrice, $maxPrice);
        $xmlPath = Storage::path('xml/class_packages.xml');
        $xslPath = Storage::path('xml/class_packages.xsl');

        $xml = new DOMDocument;
        $xml->load($xmlPath);

        $xsl = new DOMDocument;
        $xsl->load($xslPath);

        $proc = new XSLTProcessor;
        $proc->importStyleSheet($xsl);

        $base_url = url('storage\\');
        $base_url = str_replace('\\', '/', $base_url);
        $href = route('class_package.index');
        $proc->setParameter('', 'base_url', $base_url);
        $proc->setParameter('', 'href', $href);
        $proc->setParameter('', 'current_page', $currentPage);
        $proc->setParameter('', 'items_per_page', $itemsPerPage);
        $proc->setParameter('', 'query', $query);
        $proc->setParameter('', 'min_price', $minPrice);
        $proc->setParameter('', 'max_price', $maxPrice);
        $proc->setParameter('', 'sort', $sort);
        $proc->setParameter('', 'current_date', Carbon::now()->format('Ymd'));

        $html = $proc->transformToXML($xml);

        return $html;
    }
}

