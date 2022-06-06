<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileFormRequest;
use Illuminate\Support\Facades\Response;
// Packages responsible for reading of manipulating the types of file
use Smalot\PdfParser\Parser;           // Manipulates PDF
use BayAreaWebPro\SimpleCsv\SimpleCsv; // Manipulates CSV

class FileController extends Controller
{
    public function index()
    {
        return view('file');
    }

    /**
     *
     * @param App\Http\Requests\FileFormRequest $request
     *
     * @return
     */
    public function readFile(FileFormRequest $request)
    {
        $file = $request->file;

        $content = $this->getText($file);

        $fileName = str_replace('.pdf', '', $file->getClientOriginalName());

        $content = str_replace("\n", " ", $content);
        $content = preg_replace('/\s+/u', ' ', $content);

        preg_match_all('/\d{8},[a-z0-9\s]+,\s+\d{3},\s+\d{3}.\d{2}/iu', $content, $match);
        $data = $match[0];

        switch ($request->choosenType) {
            case 'csv':
                return $this->toCsv($data, $fileName);
                break;

            case 'xlsx':
                return $this->toXlsx($data, $fileName);
                break;
        }
        return redirect()->back();
    }

    /**
     * It reads the content of the PDF and return it as a string
     *
     * @param File $file
     *
     * @return string[]
     */
    private function getText(object $file)
    {
        $pdfParser = new Parser();
        $pdf = $pdfParser->parseFile($file->path());
        $content = $pdf->getText();

        return $content;
    }

    /**
     * Transform data array into a downloadable .csv
     *
     * @param array  $data     File's content
     * @param string $fileName File's name
     *
     * @return response
     */
    private function toCsv(array $data, string $fileName)
    {
        $arrayToExport = array();

        foreach ($data as $row) {
            $row = explode(",", $row);
            $temp = array();

            foreach ($row as $value) {
                array_push($temp, trim($value));
            }

            array_push($arrayToExport, $temp);
        }

        return SimpleCsv::download($arrayToExport, "$fileName.csv");
    }

    /**
     * Transform data array into a downloadable .xlsx
     *
     * @param array  $data     File's content
     * @param string $fileName File's name
     *
     * @return response
     */
    private function toXlsx(array $data, string $fileName)
    {
        $arrayToExport = array();

        foreach ($data as $row) {
            $row = explode(",", $row);
            $temp = array();

            foreach ($row as $value) {
                array_push($temp, trim($value));
            }

            array_push($arrayToExport, $temp);
        }

        $xlsx = 'Shuchkin\SimpleXLSXGen'::fromArray($arrayToExport);
        return  $xlsx->downloadAs("$fileName.xlsx");
    }
}
