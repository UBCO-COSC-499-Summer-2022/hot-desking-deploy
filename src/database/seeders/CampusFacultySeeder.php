<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\Campuses;
use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Database\Seeder;

class CampusFacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create campuses
        $campusV = Campuses::find(2);
       

        $campusO = Campuses::find(1);
       

        // create faculties for the vancouver campus
        $facultyAS = new Faculty;
        $facultyAS->faculty = 'Faculty of Applied Science';
        $facultyAS->campus_id = $campusV->id;
        $facultyAS->save();

        $facultyALA = new Faculty;
        $facultyALA->faculty = 'Faculty of Architecture and Landscape Architecture';
        $facultyALA->campus_id = $campusV->id;
        $facultyALA->save();



        // Departments for the applied sciences faculty
   
   

   

       

        // Faculty and department of arts ubcV
        $facultyARTS = new Faculty;
        $facultyARTS->faculty = 'Faculty of Arts';
        $facultyARTS->campus_id = $campusV->id;
        $facultyARTS->save();


       
    

 


        // Faculty of Audio Audiology and Speech Science
        $facultyASS = new Faculty;
        $facultyASS->faculty = 'Audiology and Speech Sciences';
        $facultyASS->campus_id = $campusV->id;
        $facultyASS->save();


        // Faculty of Business
        $facultyBUS = new Faculty;
        $facultyBUS->faculty = 'Sauder School of Business';
        $facultyBUS->campus_id = $campusV->id;
        $facultyBUS->save();


 



        // Community and Regional Planning, School of
        $faculty = new Faculty;
        $faculty->faculty = 'Community and Regional Planning';
        $faculty->campus_id = $campusV->id;
        $faculty->save();



        // Dentistry
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Dentistry';
        $faculty->campus_id = $campusV->id;
        $faculty->save();



        // Education 
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Education';
        $faculty->campus_id = $campusV->id;
        $faculty->save();

   

        // Extended Learning
        $faculty = new Faculty;
        $faculty->faculty = 'Extended Learning';
        $faculty->campus_id = $campusV->id;
        $faculty->save();


        // Forestry
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Forestry';
        $faculty->campus_id = $campusV->id;
        $faculty->save();

    

        // Graduate and Postdoctoral Studies
        $faculty = new Faculty;
        $faculty->faculty = 'Graduate and Postdoctoral Studies';
        $faculty->campus_id = $campusV->id;
        $faculty->save();


        // Journalism
        $faculty = new Faculty;
        $faculty->faculty = 'School of Journalism';
        $faculty->campus_id = $campusV->id;
        $faculty->save();



        // Journalism
        $faculty = new Faculty;
        $faculty->faculty = 'School of Kinesiology';
        $faculty->campus_id = $campusV->id;
        $faculty->save();

        
        // Journalism
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Land and Food Systems';
        $faculty->campus_id = $campusV->id;
        $faculty->save();

  

        // Journalism
        $faculty = new Faculty;
        $faculty->faculty = 'Peter A. Allard School of Law';
        $faculty->campus_id = $campusV->id;
        $faculty->save();


        // Library, Archival and Information Studies, School of
        $faculty = new Faculty;
        $faculty->faculty = 'School of Library, Archival and Information Studies';
        $faculty->campus_id = $campusV->id;
        $faculty->save();
        


        // Medicine
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Medicine';
        $faculty->campus_id = $campusV->id;
        $faculty->save();

  


     
   

     

  




        // Music
        $faculty = new Faculty;
        $faculty->faculty = 'School of Music';
        $faculty->campus_id = $campusV->id;
        $faculty->save();



        // Music
        $faculty = new Faculty;
        $faculty->faculty = 'School of Nursing';
        $faculty->campus_id = $campusV->id;
        $faculty->save();


        // School of population and Public Health
        $faculty = new Faculty;
        $faculty->faculty = 'School of Population and Public Health';
        $faculty->campus_id = $campusV->id;
        $faculty->save();

 
        
        // School of population and Public Health
        $faculty = new Faculty;
        $faculty->faculty = 'School of Public Policy and Global Affairs';
        $faculty->campus_id = $campusV->id;
        $faculty->save();

     

        // Pharmaceutical Sciences, Faculty of
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Pharmaceutical Sciences';
        $faculty->campus_id = $campusV->id;
        $faculty->save();



        // science
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Science';
        $faculty->campus_id = $campusV->id;
        $faculty->save();






 

        // social work
        $faculty = new Faculty;
        $faculty->faculty = 'School of Social Work';
        $faculty->campus_id = $campusV->id;
        $faculty->save();



        // UBC Vantage College
        $faculty = new Faculty;
        $faculty->faculty = 'UBC Vantage College';
        $faculty->campus_id = $campusV->id;
        $faculty->save();


        // Irving K. Barber Faculty of Arts and Social Sciences
        $faculty = new Faculty;
        $faculty->faculty = 'Irving K. Barber Faculty of Arts and Social Sciences';
        $faculty->campus_id = $campusO->id;
        $faculty->save();

     

        // Faculty of Creative and critical Studies
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Creative and Critical Studies';
        $faculty->campus_id = $campusO->id;
        $faculty->save();


        // Okanagan School of Education
        $faculty = new Faculty;
        $faculty->faculty = 'Okanagan School of Education';
        $faculty->campus_id = $campusO->id;
        $faculty->save();



        // School of Engineering
        $faculty = new Faculty;
        $faculty->faculty = 'School of Engineering';
        $faculty->campus_id = $campusO->id;
        $faculty->save();



        // Faculty of Health and Social Development
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Health and Social Development';
        $faculty->campus_id = $campusO->id;
        $faculty->save();


        // Faculty of Health and Social Development
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Management';
        $faculty->campus_id = $campusO->id;
        $faculty->save();

        

        // Irving K. Barber Faculty of Science
        $faculty = new Faculty;
        $faculty->faculty = 'Irving K. Barber Faculty of Science';
        $faculty->campus_id = $campusO->id;
        $faculty->save();

   

        // Faculty of Medicine Southern Medical Program
        $faculty = new Faculty;
        $faculty->faculty = 'Faculty of Medicine Southern Medical Program';
        $faculty->campus_id = $campusO->id;
        $faculty->save();


        // College of Graduate Studies
        $faculty = new Faculty;
        $faculty->faculty = 'College of Graduate Studies';
        $faculty->campus_id = $campusO->id;
        $faculty->save();

      
    }
}