import java.io.*;
import java.util.*;
import java.text.*;
import java.math.*;
import java.util.regex.*;


public class Conv {

	    public static void main(String[] args) throws IOException {
	    
	      BufferedReader buf = null ;
	      String file = args[0];
	      String put = args[1];//"/home/sam/Desktop/MTP/dat/load/";
	      File file1 = new File(put+file+"_r.csv");
	      file1.createNewFile();
	      File file2 = new File(put+file+"_s.csv");
	      file2.createNewFile();
	      File filer = new File(put+file+".csv");
	      FileWriter f1 = new FileWriter(file1);
	      FileWriter f2 = new FileWriter(file2);	
	      Scanner sc = new Scanner(filer);
	      int count = 0;
	      HashMap<String,Integer> hash = new HashMap<String,Integer>();	
	      int pos = 1;
	      String route = "";
	      while (sc.hasNextLine()) {
	    	  String b = sc.nextLine();
	    	  String[] temp  = b.split(",");
	    	  temp[0].trim();
	    	  String stop = temp[1].trim();
	    	  String lat = temp[2].trim();
	    	  String lon = temp[3].trim();
	    	  if(temp[0].equals(route)){
	    		  pos++;
	    	  }
	    	  else{
	    		  route = temp[0];
	    		  pos = 1;
	    	  }
	    	  if(hash.get(stop)==null){
	    		  hash.put(stop,count);
	    		  f1.write(route+","+count+","+pos);
	    		  f1.write("\n");
	    		  f2.write(count+","+stop+","+lat+","+lon);
	    		  f2.write("\n");
	    		  count++;
	    	  }
	    	  else{
	    		  int val = (int) hash.get(stop);
	    		  f1.write(route+","+val+","+pos);
	    		  f1.write("\n");
	    	  }
	      }	     
	      f1.close();
	      f2.close();
	    
	    }
    
}

