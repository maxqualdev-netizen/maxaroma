<?
namespace App\Http\Controllers;
use App\Models\TempUser;
use Illuminate\Http\Request;

class TempUserController extends Controller
{    
    public function index()
    {
        // Retrieve all users with their related posts
        $users = TempUser::with('posts')->get();
        // Return users data (could also return a view with this data)
        return response()->json($users);
    }
}
?>