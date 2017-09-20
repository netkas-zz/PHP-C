using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Collections;
using System.Data;
using System.Diagnostics;

namespace PHP_C_Client
{
    class Program
    {

        private static System.Collections.Specialized.NameValueCollection PostData = new System.Collections.Specialized.NameValueCollection();
        private static PHP_C.Client Client;
        private static PHP_C.Client.ResponseData Response;
        private static string key;

        static void Main(string[] args)
        {


            Console.WriteLine("Connecting...");
            try
            {
                Client = new PHP_C.Client(new Uri("http://localhost/php-c/PHP-C/"), true, "root", "admin");
                Console.WriteLine("Connected!");
            }
            catch (Exception ex)
            {
                Console.WriteLine(string.Format("Error: {0}", ex.Message));
            }
            string INPUT = null;
            do
            {
                Console.Write("> ");
                INPUT = Console.ReadLine().ToLower();

                switch (INPUT)
                {
                    case "add":
                        Console.Write("Key: ");
                        string Key = Console.ReadLine();
                        Console.Write("Value: ");
                        string Value = Console.ReadLine();
                        PostData.Add(Key, Value);

                        break;
                    case "print":
                        Console.WriteLine(" ------ ");
                        foreach (string key_loopVariable in PostData.Keys)
                        {
                            key = key_loopVariable;
                            foreach (string value in PostData.GetValues(key))
                            {
                                Console.WriteLine(key + " - " + value);
                            }
                        }

                        Console.WriteLine(" ------ ");

                        break;
                    case "clear":
                        PostData.Clear();

                        break;
                    case "send":
                        Response = Client.sendRequest(PostData);
                        Console.WriteLine(Response.ToString());
                        Console.WriteLine(Response.Data.ToString());
                        break;
                }
            } while (true);

        }
    }
}
