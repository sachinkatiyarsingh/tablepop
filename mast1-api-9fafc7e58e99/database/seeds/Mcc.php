<?php

use Illuminate\Database\Seeder;

class Mcc extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('merchant_codes')->delete();
        $countries = array(
            array('id' => 1,'code' => '7623' ,'category' => "Accounting/Bookkeeping Services"),
            array('id' => 2,'code' => '8931' ,'category' => "Accounting/Bookkeeping Services"),
            array('id' => 3,'code' => '7311' ,'category' => "Advertising Services"),
            array('id' => 4,'code' => '4511' ,'category' => "Airlines, Air Carriers"),
            array('id' => 5,'code' => '0763' ,'category' => "Agricultural Cooperative"),
            array('id' => 6,'code' => '4582' ,'category' => "Airports, Flying Fields"),
            array('id' => 7,'code' => '4119' ,'category' => "Ambulance Services"),
            array('id' => 8,'code' => '7996' ,'category' => "Amusement Parks/Carnivals"),
            array('id' => 9,'code' => '5937' ,'category' => "Antique Reproductions"),
            array('id' => 10,'code' => '5932','category' => "Antique Shops"),
            array('id' => 11,'code' => '7998','category' => "Aquariums"),
            array('id' => 12,'code' => '8911','category' => "Architectural/Surveying Services"),
            array('id' => 13,'code' => '5971','category' => "Art Dealers and Galleries"),
            array('id' => 14,'code' => '5970','category' => "Artists Supply and Craft Shops"),
            array('id' => 15,'code' => '7531','category' => "Auto Body Repair Shops"),
            array('id' => 16,'code' => '7535','category' => "Auto Paint Shops"),
            array('id' => 17,'code' => '7538','category' => "Auto Service Shops"),
            array('id' => 18,'code' => '5531','category' => "Auto and Home Supply Stores"),
            array('id' => 19,'code' => '6011','category' => "Automated Cash Disburse"),
            array('id' => 20,'code' => '5542','category' => "Automated Fuel Dispensers"),
            array('id' => 21,'code' => '8675','category' => "Automobile Associations"),
            array('id' => 22,'code' => '5533','category' => "Automotive Parts and Accessories Stores"),
            array('id' => 23,'code' => '5532','category' => "Automotive Tire Stores"),
            array('id' => 24,'code' => '9223','category' => "Bail and Bond Payments (payment to the surety for the bond, not the actual bond paid to the government agency)"),
            array('id' => 25,'code' => '5462','category' => "Bakeries"),
            array('id' => 26,'code' => '7929','category' => "Bands, Orchestras"),
            array('id' => 27,'code' => '7230','category' => "Barber and Beauty Shops"),
            array('id' => 28,'code' => '7995','category' => "Betting/Casino Gambling"),
            array('id' => 29,'code' => '5940','category' => "Bicycle Shops"),
            array('id' => 30,'code' => '7932','category' => "Billiard/Pool Establishments"),
            array('id' => 31,'code' => '5551','category' => "Boat Dealers"),
            array('id' => 32,'code' => '4457','category' => "Boat Rentals and Leases"),
            array('id' => 33,'code' => '5942','category' => "Book Stores"),
            array('id' => 34,'code' => '5192','category' => "Books, Periodicals, and Newspapers"),
            array('id' => 35,'code' => '7933','category' => "Bowling Alleys"),
            array('id' => 36,'code' => '8244','category' => "Business/Secretarial Schools"),
            array('id' => 37,'code' => '7278','category' => "Buying/Shopping Services"),
            array('id' => 38,'code' => '4899','category' => "Cable, Satellite, and Other Pay Television and Radio"),
            array('id' => 39,'code' => '5946','category' => "Camera and Photographic Supply Stores"),
            array('id' => 40,'code' => '5441','category' => "Candy, Nut, and Confectionery Stores"),
            array('id' => 41,'code' => '7512','category' => "Car Rental Agencies"),
            array('id' => 42,'code' => '7542','category' => "Car Washes"),
            array('id' => 43,'code' => '5511','category' => "Car and Truck Dealers (New & Used) Sales, Service, Repairs Parts and Leasing"),
            array('id' => 44,'code' => '5521','category' => "Car and Truck Dealers (Used Only) Sales, Service, Repairs Parts and Leasing"),
            array('id' => 45,'code' => '1750','category' => "Carpentry Services"),
            array('id' => 46,'code' => '7217','category' => "Carpet/Upholstery Cleaning"),
            array('id' => 47,'code' => '5811','category' => "Caterers"),
            array('id' => 48,'code' => '8398','category' => "Charitable and Social Service Organizations - Fundraising"),
            array('id' => 49,'code' => '5169','category' => "Chemicals and Allied Products (Not Elsewhere Classified)"),
            array('id' => 50,'code' => '8351','category' => "Child Care Services"),
            array('id' => 51,'code' => '5641','category' => "Childrens and Infants Wear Stores"),
            array('id' => 52,'code' => '8049','category' => "Chiropodists, Podiatrists"),
            array('id' => 53,'code' => '8041','category' => "Chiropractors"),
            array('id' => 54,'code' => '5993','category' => "Cigar Stores and Stands)"),
            array('id' => 55,'code' => '8641','category' => "Civic, Social, Fraternal Associations"),
            array('id' => 56,'code' => '7349','category' => "Cleaning and Maintenance"),
            array('id' => 57,'code' => '7296','category' => "Clothing Rental"),
            array('id' => 58,'code' => '8220','category' => "Colleges, Universities"),
            array('id' => 59,'code' => '5046','category' => "Commercial Equipment (Not Elsewhere Classified)"),
            array('id' => 60,'code' => '5139','category' => "Commercial Footwear"),
            array('id' => 61,'code' => '7333','category' => "Commercial Photography, Art and Graphics"),
            array('id' => 62,'code' => '4111','category' => "Commuter Transport, Ferries"),
            array('id' => 63,'code' => '4816','category' => "Computer Network Services"),
            array('id' => 64,'code' => '7372','category' => "Computer Programming"),
            array('id' => 65,'code' => '7379','category' => "Computer Repair"),
            array('id' => 66,'code' => '5734','category' => "Computer Software Stores"),
            array('id' => 67,'code' => '5045','category' => "Computers, Peripherals, and Software"),
            array('id' => 68,'code' => '1771','category' => "Concrete Work Services"),
            array('id' => 69,'code' => '5039','category' => "Construction Materials (Not Elsewhere Classified)"),
            array('id' => 70,'code' => '7392','category' => "Consulting, Public Relations	"),
            array('id' => 71,'code' => '8241','category' => "Correspondence Schools"),
            array('id' => 72,'code' => '5977','category' => "Cosmetic Stores"),
            array('id' => 73,'code' => '7277','category' => "Counseling Services"),
            array('id' => 74,'code' => '7997','category' => "Country Clubs"),
            array('id' => 75,'code' => '4215','category' => "Courier Services"),
            array('id' => 76,'code' => '9211','category' => "Court Costs, Including Alimony and Child Support - Courts of Law"),
            array('id' => 77,'code' => '7321','category' => "Credit Reporting Agencies"),
            array('id' => 78,'code' => '4411','category' => "Cruise Lines"),
           
            );
            DB::table('merchant_codes')->insert($countries);
    }
}
